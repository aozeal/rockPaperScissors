<?php
/*
40 じゃんけんを作成しよう！
下記の要件を満たす「じゃんけんプログラム」を開発してください。
要件定義
・使用可能な手はグー、チョキ、パー
・勝ち負けは、通常のじゃんけん
・PHPファイルの実行はコマンドラインから。
ご自身が自由に設計して、プログラムを書いてみましょう！
*/

echo "Lesson 40" . PHP_EOL;


const STR_HAND_GUIDANCE = "1:グー、2:チョキ、3:パー です";

const ROCK = 0;
const SCISSORS = 1;
const PAPER = 2;

//声を出すイメージの文字列
const HAND_CALLS = array(
	ROCK => "グー",
	SCISSORS => "チョキ",
	PAPER => "パー"
);

function getHandCall($hand){
	return HAND_CALLS[$hand - 1];
}

//実際にジャンケンを出しているイメージを絵文字
const HAND_ICONS = array(
	"✊", 
	"✌️", 
	"✋"
);

function getHandIcon($hand){
	return HAND_ICONS[$hand -1];
}

const DRAW = 0;
const LOSE = 1;
const WIN = 2;
const STR_RESULTS = array(
	DRAW => "あいこ",
	LOSE => "負け",
	WIN => "勝ち"
);

function getComHand(){
	return random_int(1, 3);
}


function getWinRate($results){
	return round(100 * $results[WIN] / ($results[WIN] + $results[LOSE]));
}

function getIsReplay(){
	echo "リプレイ？(y/n)：";
	$input = trim(fgets(STDIN)); //標準入力

	if ($input == "Y" || $input == "y"){
		return true;
	}
	if ($input == "N" || $input == "n"){
		return false;
	}
	return getIsReplay();
}


function playGame(){
	//どうでも良い機能(最初はグー）
	do{
		echo "最初は...";
		$input = getYourHand();
		echo getHandCall($input) . PHP_EOL;
		
		if ($input !== 1){
			echo "もう一回！" . PHP_EOL;
		}

	}while ($input !== 1);


	//以下、本編
	echo "じゃん、けん ...";

	//ジャンケン（中身）
	$result = rockPaperScissors();

	//結果表示
	echo sprintf("あなたの%s\n", STR_RESULTS[$result]);

	return $result;
}


function rockPaperScissors(){

	//自分のジャンケンを入力
	$your_hand = getYourHand();
    echo getHandCall($your_hand) . PHP_EOL;

	//相手のジャンケンを決める
	$com_hand = getComHand();
	
	echo PHP_EOL;
	echo sprintf("あなた%s vs %s 相手\n\n", getHandIcon($your_hand), getHandIcon($com_hand));

	//勝ち負けを判定
	$result = judgement($your_hand, $com_hand);

    //あいこの場合は再帰
	if ($result === DRAW){
		echo "あいこで...";
		return rockPaperScissors();
	}

	return $result;
}


function getYourHand(){
	$input = trim(fgets(STDIN)); //標準入力

	$input = validHandInput($input);
	if ($input === false){
		echo STR_HAND_GUIDANCE;
		return getYourHand();
	}

	return $input;
}

//1,2,3でない場合はfalse、それ以外は1,2,3,を返す
function validHandInput($input){
	if ($input === ""){
		return false;
	}
	if (!is_numeric($input)){
		return false;
	}
	//小数点に対応
	$input = (int)$input;

	if ($input < 1){
		return false;
	}
	if ($input > 3){
		return false;
	}
	return $input;
}

//0:あいこ, 1:負け、2:勝ち　を判定する
function judgement($your_hand, $com_hand){
	return ($your_hand - $com_hand + 3) % 3;
}



//以下本編

echo STR_HAND_GUIDANCE . PHP_EOL . PHP_EOL;

//0:あいこは使わない
$game_results = [0, 0, 0];

do{
	//ジャンケンして結果を登録
	$result = playGame();
	$game_results[$result]++;

	$is_replay = getIsReplay();

} while($is_replay);

//最後に勝率を表示して終了
echo sprintf("%s：%d , ", STR_RESULTS[WIN], $game_results[WIN]);
echo sprintf("%s：%d , ", STR_RESULTS[LOSE], $game_results[LOSE]);
echo sprintf("勝率：%d%% \n", getWinRate($game_results));


?>
