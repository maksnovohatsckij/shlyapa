const task = ["play", "next", "newgame", "clear", "addwords", "info"];

let settings = {
  url: "./manager/manager.php",
  type: "POST",
  dataType: "html",
  data: {
    message: "",
    task: "",
    gamename: "",
  },
};

let scores = 0;
let pushable = true;
let inGame = false;
let dataArr = [];
let word = "";
let lastChanse = false;

$(document).ready(() => {
  $("#menu").hide();
  $("#next").hide();
  $("#play").hide();
  $("#menuopen").hide();
  setGameName().then(preparePage, setGameName);
  getActions();
});

function preparePage() {
  $("#play").fadeIn(30);
  $("#menuopen").fadeIn(100);
  getScores();
  $("#msg").html(
    `<small><small>Откройте меню, чтобы положить слова в шляпу и затем запустить кон. Нажмите "ход", чтобы взять слова из шляпы.</small></small>`
  );
  $("#msg small").fadeIn(20);
  $("#menuopen").click(() => $("#menu").fadeIn(100));
  $("#menuclose").click(() => $("#menu").fadeOut(150));
  $("#play").click(() => request(0));
  $("#next").click(pushNext);
  $("#newgame").click(() => request(2));
  $("#clear").click(() => request(3));
  $("#addwords").click(() => request(4));
  $("#infobutton").click(() => request(5));
  $("#nullScores").click(resetScores);
  $("#randomiser").click(shuffle);
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
function pushNext() {
  if (pushable) {
    pushable = false;
    return request(1);
  }
}

function request(num) {
  settings.data.task = task[num];
  putMsgVal(num);
  $.ajax(settings).done((data) => fSucces(data, num));
}

function putMsgVal(num) {
  let str = "";
  if (num == 1) {
    str += dataArr.join("\r\n");
  } else if (num == 4) {
    $.each($(".newword"), (i, elem) => {
      str += elem.value ? String(elem.value) + "\r\n" : "";
      elem.value = "";
    });
  }
  settings.data.message = str;
}

function fSucces(data, num) {
  if (num > 1) {
    successMenu(data, num);
  } else {
    successGame(data, num);
  }
}

function successMenu(data, num) {
  if (num == 5) {
    $("#information").html(
      data
        ? "В игре всего " +
            data.split("\r\n").filter((item) => (item ? true : false)).length +
            " слов"
        : "В игре всего 0 слов. Добавьте слова, чтобы начать игру."
    );
    play.coin();
  } else {
    $("#information").html(data ? data : "Пустой ответ сервера");
    play.upload();
  }
}

function successGame(data, num) {
  getWord(data);

  stoptimer = lastChanse ? true : word ? false : true;

  if (stoptimer) {
    $("#next").hide();
    $("#play").show();
    $("#timer").text(""); //
    $("#menuopen").fadeIn(50);
    inGame = false;
  }

  if (num == 1) {
    play.coin();
    pushable = lastChanse ? false : true;
    scores++;
    $("header span").text("очки: " + scores);
    if (!word) setTimeout(play.endgame, 600);
    setScores(scores);
  } else if (num == 0 && word) {
    play.upload();
    timer();
    pushable = true;
    $("header span").text("очки: " + scores);
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
    dataArr = data.split("\r\n").filter((item) => (item ? true : false));
    if (dataArr) {
      let daNum = random(dataArr.length - 1);
      word = dataArr[daNum];
      dataArr.splice(daNum, 1);
    }
  } else {
    dataArr = [];
    word = undefined;
  }
}

function timer() {
  let time = 30;
  let delay = 6.6;
  $("#play").hide();
  $("#next").show();
  $("#menuopen").hide();
  $("#next").text("Следующее слово");
  lastChanse = false;
  inGame = true;
  timeout(time, delay);
}

function timeout(time, delay) {
  if (!stoptimer) {
    if (time > 0) {
      $("#timer").text(time);
      time -= 1;
      setTimeout(() => timeout(time, delay), 1000);
    } else if (time == 0) {
      play.warning();
      $("#timer").text(time);
      time -= 1;
      $("#next").text("Последний шанс");
      lastChanse = true;
      setTimeout(() => timeout(time, delay), delay * 1000);
      inGame = false;
      play.clock();
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
}

function setGameName() {
  return new Promise((resolve, reject) => {
    $("#setgamename").show();
    $("#selectnamebutton").click(() => {
      let val = document.getElementById("selectname").value;
      $("#setgamename").fadeOut(150);
      if (val) {
        settings.data.gamename = val;
        resolve();
      } else {
        console.log("errorGameName");
        reject();
      }
    });
  });
}

function shuffle() {
  let array = (prompt("Ведите имена участников через пробел") || "")
    .split(" ")
    .filter((item) => (item ? true : false));
  for (let i = array.length - 1; i > 0; i--) {
    let j = random(i);
    [array[i], array[j]] = [array[j], array[i]];
  }
  let str = "";
  let pair = true;
  array.forEach((item) => {
    str += pair ? item : ` - ${item} <br>`;
    pair = pair ? false : true;
  });
  $("#randomiser-res").html(str);
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

function getLastAction(i, elem) {
  $.ajax({
    url: "./manager/manager.php",
    type: "POST",
    dataType: "html",
    data: {
      task: "getactions",
      gamename: elem.value,
    },
  }).done((data) => {
    $(`<br><span> ${data ? "изм. " + data : " "}</span>`).appendTo(elem);
  });
}

function getActions() {
  $.each($(".s_room"), getLastAction);
}
