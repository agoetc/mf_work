function resize(){
	areaoj=document.getElementById("message");
	tval = areaoj.value;//テキストエリアの文字取得

	//改行文字の数を取得
	num = tval.match(/\n|\r\n/g);

//改行文字の数に合せて高さを変更
	if (tval==""){
		areaoj.style.height ="1.4em";
		return;
	}
	
	if (num!=null){
		len = num.length+1;
	} else {
		areaoj.style.height ="1.4em";
		return;
	}
	
	len=len+1;
	/*
	if(len==3) {
		return;
	}
	*/
	
	areaoj.style.height = len * 1.05 + "em";
}