document.addEventListener('DOMContentLoaded', function() {

  // Initialisation
  let conn, ident = null;
  let isConnected = false;
  let uri = 'ws://127.0.0.1:8080/';

  // Ciblages
  let zon_iden = document.querySelector("#zon_iden");
  let btn_iden = document.querySelector("#zon_iden button");
  let get_iden = document.querySelector("#zon_iden input");
  let zon_text = document.querySelector("#zon_text");
  let aff_text = document.querySelector("#aff_tcha ul");
  let get_text = document.querySelector("#aff_tcha input");
  let aff_list = document.querySelector('#aff_list');

  // EVENT appui sur ENTRÉE
  document.addEventListener('keydown', function(e){
    get_text.focus();
    if (e.key=='Enter') {
      if (isConnected == true) {
        send();
      } else {
        toggleConnect();
      }
    }
  });

  // EVENT appui sur btn CONN
  btn_iden.addEventListener('click', function() {
    toggleConnect();
  });

  function setOnline() {
    zon_iden.classList.add("on");
    get_iden.setAttribute('disabled', 'true');
    zon_text.classList.add("on");
    get_text.focus();
    isConnected = true;
  }

  function setOffline() {
    zon_iden.classList.remove("on");
    get_iden.removeAttribute('disabled');
    get_iden.value="";
    zon_text.classList.remove("on");
    isConnected = false;
  }

  function send() {
    msg = get_text.value;
    if (msg == "") {
      alert("Votre message ne peut être vide...");
      return;
    } else if (ident == ""){
      alert("Votre identité est inconnue...");
      return;
    }
    conn.send('{"msg":"'+msg+'"}');
    let tempLI = document.createElement('li');
    tempLI.textContent = "Moi : " + msg;
    aff_text.appendChild(tempLI);
    get_text.value = '';
  }

  function identification() {
    conn.send('{"ident":"'+ident+'"}');
  }

  function toggleConnect() {
    if (isConnected) {
      if (conn) {
        conn.close();
      }
      setOffline();
      return;
    }

    ident = get_iden.value;
    if (ident!='') {
      conn = new WebSocket(uri);
      setOnline();

      conn.onmessage = function(e) {
        obj_temp = JSON.parse(e.data);
        let tempLI = document.createElement('li');
        if (obj_temp.ident=="ADMIN") {
          tempLI.className = "admin";
        }
        tempLI.textContent = obj_temp.ident + " : " + obj_temp.msg;
        aff_text.appendChild(tempLI);
      };

      conn.onopen = function(e) {
        console.log(ident + " connecté");
        identification();
      };

      conn.onclose = function(e) {
        console.log(ident + " déconnecté");
        setOffline();
      };
    }

  }

});
