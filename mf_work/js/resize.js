function resize() {
	textarea = document.getElementById("message");
	
	//テキストエリアの文字取得
	tval = textarea.value;

	//改行文字の数を取得
	num = tval.match(/\n|\r|\r\n/g);

	//改行文字の数に合せて高さを変更
	if (tval == "") {
		textarea.style.height = "1.5em";
		return;
	}
	
	if (num != null) {
		len = num.length + 1;
	} else {
		textarea.style.height = "1.5em";
		return;
	}
	
	len += 1;
	
	textarea.style.height = len * 1.05 + "em";
}