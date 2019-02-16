/**
 * jogavelha.js
 * Contem funções do jogo da velha.
 */

var board = [];
var n;
var blanks; 
var boardref = [];
var currentPlayer = false;
var players = {};
var message = document.getElementById("msg");


function init(size = 3)
{
	n = size;
	blanks = n*n;
	let tbl = document.getElementById("board").children[0]; 
	tbl.innerHTML = ""; 
	message.innerHTML = "";
	let newRow = [];
	
	for (let i = 0; i < size; i++) {
		board[i] = [];
		boardref[i] = [];

		newRow = tbl.insertBefore(document.createElement("tr"), null);
		

		for (let j = 0; j < size; j++) 
		{
	
			board[i][j] = 0;
			

			boardref[i][j] = newRow.insertBefore(document.createElement("td"), null);
			

			if (i == 0) {
				if (j == 0) {
					boardref[i][j].classList.add("A1");
				}
				else if (j == (size - 1)) {
					boardref[i][j].classList.add("C1"); 
				}
				else {
					boardref[i][j].classList.add("B1");
				}
			}
			else if (i == (size - 1)) {
				if (j == 0) {
					boardref[i][j].classList.add("A3"); 
				}
				else if (j == (size - 1)) {
					boardref[i][j].classList.add("C3"); 
				}
				else {
					boardref[i][j].classList.add("B3");
				}
			}
			else {
				if (j == 0) {
					boardref[i][j].classList.add("A2");
				}
				else if (j == (size - 1)) {
					boardref[i][j].classList.add("C2");
				}
				else {
					boardref[i][j].classList.add("B2");
				}
			}
		}
	}
	
}

function draw() {
	for (let i = 0; i < n; i++) {
		for (let j = 0; j < n; j++) {
			boardref[i][j].innerHTML = "";
			let type;
			switch (board[i][j]) {
				case 1:
					type = "X";
					break;
				case -1:
					type = "O";
					break;
				case 0:
					type = "blank";
					break;
			}
			img = boardref[i][j].insertBefore(createImg(type), null);			

			if ((currentPlayer !== false) && (type === "blank")) {
				img.onclick = function () {
					nextTurn(i.toString() + j.toString());
				};
			}
		}
	}
}


function win()
{

	var sequence = [];
	

	for (let i = 0; i < (2*n + 2); i++) {
		sequence[i] = [];
	}
		
	for (let i = 0; i < n; i++) 
	{

		sequence[0][i] = board[i][i];			
		sequence[1][i] = board[n - 1 - i][i];

		for (let j = 0; j < n; j++) {
			sequence[2 + i][j] = board[j][i];		
			sequence[2 + n + i][j] = board[i][j];	
		}
	}

	var winner = 0;
	for (let i = 0; (i < (2*n + 2)) && !winner; i++) 
	{
		winner = isWinSeq(sequence[i]);
	}
	
	// checa por empate
	if (!winner) {
		if (blanks == 0) {
			winner = 0; 
		}
		else {
			winner = false; 
		}
	}
	
	return winner;
}


function nextTurn(playerMove, AImove = false) 
{

	if (currentPlayer === false) {
		return false;
	}

	if ((!AImove) && (isAITurn())) {
		return false;
	}	

	if (playerMove.length != 2) {
		executeLog("Sintaxe de playerMove inválida. Deve ter exatamente 2 caracteres!");
		return false;
	}
	

	var i = parseInt(playerMove[0]);
	var j = parseInt(playerMove[1]);
	

	if ((i < 0) || (i >= n) || (j < 0) || (j >= n)) {
		executeLog("Movimento de jogador inválido - uma das coordenadas está fora dos limites!");
		executeLog(playerMove);
		return false;
	}	

	if (board[i][j] != 0) {
		executeLog("Está tentando mover uma peça ocupada!");
		executeLog(playerMove);
		return false;
	}
	

	board[i][j] = currentPlayer;
	var log =  inputStringAI();
	executeLog(log);
	blanks--;
	currentPlayer *= -1;
	draw();
	var winningState = win();
	if (winningState !== false) 
	{
		executeLog("Fim de jogo "+ winningState);
		endGame(winningState); 
	}
	
	else {

		message.innerHTML = "Jogador ";
		message.innerHTML += (currentPlayer == -1 ? "2 " : "1 ") + "sua vez.";
		if (isAITurn()) {
			executeAJAX('./jogoAi.php?' + inputStringAI(), nextTurn);
		}
	}
}


function endGame(winningState) {

	currentPlayer = false;
	switch (winningState) {
		case 1:
			message.innerHTML = "<font color=\"red\">X</font> ganhou!";
			break;
		case -1:
			message.innerHTML = "<font color=\"blue\">O</font> ganhou!";
			break;
		case 0:
			message.innerHTML = "<font color=\"black\">Empate Técnico!</font>"
	}	

	toggleForms();
}

function startGame() 
{

	toggleForms();	

	players = {
			p1: document.querySelector('input[name = "player1"]:checked').value,
			p2: document.querySelector('input[name = "player2"]:checked').value
	};
	

	currentPlayer = 1;
	let boardSize = document.getElementById("boardSizeSelection").value;
	init(parseInt(boardSize));
	draw();
	

	message.innerHTML = "Jogador 1 sua vez."
	
	var log = inputStringAI();
	executeLog(log);

	if (isAITurn()) {
		executeAJAX('./jogoAi.php?' + inputStringAI(), nextTurn);		
	}
		
}

function toggleForms() {

	let button = document.getElementById("initGame");
	button.disabled = !button.disabled;	

	for (let input of document.getElementsByTagName("input")) {
		input.disabled = !input.disabled;
	}	

	let input = document.getElementById("boardSizeSelection");
	input.disabled = !input.disabled;
}

function createImg(type) {

	var img = document.createElement("img");
	img.src = "./img/" + type + ".png";
	img.height = 128;
	img.width = 128;
	
	return img;
}


function isWinSeq(seq)
{
	var min, max;
	max = Math.max.apply(null, seq);
	min = Math.min.apply(null, seq);
	
	if ((max == min) && (max != 0)) {
		return max;
		
	}
	else {
		return false;
	}
}


function isAITurn()
{
	return ((currentPlayer == 1) && ((players.p1 === "AI1") || (players.p1 === "AI2") || (players.p1 === "AI3")) ) || 
			((currentPlayer == -1) && ((players.p2 === "AI1") || (players.p2 === "AI2") || (players.p2 === "AI3")) );
}


function inputStringAI() {
	var str = "n=" + n.toString() + "&";
	
	str += "currentPlayer=" + (currentPlayer == -1 ? "2&" : "1&");
	
	str += "board=";
	for (let i = 0; i < n; i++) {
		for (let j = 0; j < n; j++) {
			str += i.toString() + j.toString();
			if (board[i][j] == 0) {
				str += "0 ";
			}
			else {
				str += (board[i][j] == -1 ? "2 " : "1 ");
			}
		}
	}

	str = str.slice(0, -1);
	str += "&AI=" + (currentPlayer == 1 ? players.p1 : players.p2);
	return str;
}

