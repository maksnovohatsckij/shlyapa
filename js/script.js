let gameroom = "";
let scores = 0;
let next_pushable = true;
let inGame = false;
let word = "";
let lastChanse = false;
let players = "";

$(document).ready(() => {
  $("#menu,#next,#play,#menuopen").hide();
  registration(preparePage);
});

function registration(after) {
  let res, rej;
  $("#selectname").keyup((e) => {
    e.target.value = filterName(e.target.value);
    getLastAction(e.target);
    if (e.keyCode == 13) $("#selectnamebutton").click();
  });
  $("#selectnamebutton").click(() => {
    gameroom = filterName(document.getElementById("selectname").value);
    $("#setgamename").fadeOut(150, gameroom ? res : rej);
  });
  (function setRoom() {
    new Promise((resolve, reject) => {
      res = resolve;
      rej = reject;
    }).then(after, setRoom);
    $("#setgamename").show(20);
  })();
}

function preparePage() {
  $("#current_room").html(`Комната: ${gameroom}`);
  $("#msg").html(
    `<small><small>Откройте меню, чтобы положить слова в шляпу и затем запустить кон. Нажмите "ход", чтобы взять слова из шляпы.</small></small>`
  );
  $("#msg small").fadeIn(20);
  $("#play").fadeIn(30);
  $("#menuopen").fadeIn(100);
  getScores();
  $("#menuopen").click(() => $("#menu").fadeIn(100, getPlayers));
  $("#menuclose").click(() => $("#menu").fadeOut(150));
  $("#play").click(() => request("play"));
  $("#next").click(() => {
    if (next_pushable) {
      next_pushable = false;
      request("next");
    }
  });
  $("#newgame").click(() => request("newgame"));
  $("#clear").click(() => request("clear"));
  $("#addwords").click(() => request("addwords"));
  $("#infobutton").click(() => request("info"));
  $("#nullScores").click(resetScores);
  $("#randomiser").click(shufflePlayers);
}

function setScores(scores) {
  $.ajax({
    url: "",
    type: "POST",
    dataType: "html",
    data: {
      task: "setScores",
      scores: scores,
    },
  });
}

function getScores() {
  $.ajax({
    url: "",
    type: "POST",
    dataType: "html",
    data: {
      task: "getScores",
    },
  }).done((data) => {
    scores += Number(data) || 0;
    $("header span").text("очки: " + scores);
  });
}

function resetScores() {
  scores = 0;
  setScores(scores);
  $("header span").text("очки: " + scores);
  play.coin();
  $("#information").text("Очки обнулены");
}

function request(task) {
  $.ajax({
    url: "./manager/manager.php",
    type: "POST",
    dataType: "html",
    data: {
      message: createMessage(task),
      task: task,
      gameroom: gameroom,
    },
  }).done((data) => fSucces(data, task));
}

function createMessage(task) {
  let str = "";
  if (task == "next") {
    str = word;
  } else if (task == "addwords") {
    $.each($(".newword"), (i, elem) => {
      str += elem.value ? String(elem.value) + "\r\n" : "";
      elem.value = "";
    });
  } else if (task == "setPlayers") {
    str = players;
  }
  return str;
}

function fSucces(data, task) {
  if (task == "play" || task == "next") {
    successGame(data, task);
  } else {
    successMenu(data, task);
  }
}

function successMenu(data, task) {
  if (task == "info") {
    $("#information").html(
      data
        ? "В игре всего " + data.split("\r\n").filter(Boolean).length + " слов"
        : "В игре всего 0 слов. Добавьте слова, чтобы начать игру."
    );
    play.coin();
  } else if (task == "getPlayers") {
    players = data || "";
    $("#randomiser-res").html(players);
  } else if (task == "setPlayers") {
    request("getPlayers");
  } else {
    $("#information").html(data ? data : "Пустой ответ сервера");
    play.upload();
  }
}

function successGame(data, task) {
  getWord(data);

  stoptimer = lastChanse ? true : word ? false : true;

  if (stoptimer) {
    $("#next").hide();
    $("#play").show();
    $("#timer").text(""); //
    $("#menuopen").fadeIn(50);
    inGame = false;
  }

  if (task == "next") {
    next_pushable = lastChanse ? false : true;
    scores++;
    $("header span").text("очки: " + scores);
    setScores(scores);
    play.coin();
    if (!word) setTimeout(play.endgame, 600);
  } else if (task == "play" && word) {
    timer();
    next_pushable = true;
    $("header span").text("очки: " + scores);
    play.upload();
  }

  if (!lastChanse) {
    $("#msg").html(word ? word : "Слова закончились");
  } else {
    $("#next").hide();
    $("#play").show();
    $("#msg").text("Зачтено" + (word ? "" : ", слова закончились"));
    $("#menuopen").fadeIn(50);
  }

  lastChanse = false;
}

function getWord(data) {
  if (data) {
    let dataArr = data.split("\r\n").filter(Boolean);
    word = dataArr ? dataArr[random(dataArr.length - 1)] : undefined;
  } else word = undefined;
}

function timer() {
  let time = 30;
  let delay = 7;
  $("#play").hide();
  $("#next").show();
  $("#menuopen").hide();
  $("#next").text("Следующее слово");
  lastChanse = false;
  inGame = true;
  (function timeout(time) {
    if (!stoptimer) {
      if (time > 0) {
        $("#timer").text(time);
        setTimeout(() => timeout(time - 1), 1000);
      } else if (time == 0) {
        $("#timer").text(time);
        $("#next").text("Последний шанс");
        lastChanse = true;
        inGame = false;
        play.warning();
        play.clock();
        setTimeout(() => timeout(time - 1), delay * 1000);
      } else if (lastChanse) {
        $("#next").hide();
        $("#play").show();
        $("#msg").text("время вышло");
        $("#timer").text("");
        $("#menuopen").fadeIn(50);
        lastChanse = false;
        play.lose();
      }
    } else if (!inGame && word) {
      play.lose();
      $("#msg").text("время вышло");
    }
  })(time);
}

function shufflePlayers() {
  let arr = (prompt("Ведите имена участников через пробел") || "")
    .split(" ")
    .filter(Boolean);
  for (let i = arr.length - 1; i > 0; i--) {
    let j = random(i);
    [arr[i], arr[j]] = [arr[j], arr[i]];
  }
  let str = "";
  let pair = false;
  arr.forEach((item) => (str += (pair = !pair) ? item : ` - ${item} <br>`));
  setPlayers(str);
}

function setPlayers(str) {
  players = str;
  request("setPlayers");
}

function getPlayers() {
  request("getPlayers");
}

function random(max) {
  return Math.floor(Math.random() * (max + 1));
}

const play = {
  upload() {
    new Audio("./sounds/upload.mp3").play();
  },
  endgame() {
    new Audio("./sounds/endgame.mp3").play();
  },
  lose() {
    new Audio("./sounds/lose.mp3").play();
  },
  warning() {
    new Audio("./sounds/warning.mp3").play();
  },
  coin() {
    new Audio("./sounds/coin.mp3").play();
  },
  clock() {
    new Audio("./sounds/clock.mp3").play();
  },
};

function getLastAction(elem) {
  $.ajax({
    url: "./manager/manager.php",
    type: "POST",
    dataType: "html",
    data: {
      task: "getactions",
      gameroom: elem.value,
    },
  }).done((data) => {
    $(`#gameroom-info`).html(data ? "Посл. активность:" + data : "");
  });
}

function filterName(str) {
  return str.replace(
    /@|;|:|\.|,|\/|\\|\||\$|\?|!|#|%|\*|\^|\+|=|\[|\]| |\ |«|<|>/gi,
    ""
  );
}
