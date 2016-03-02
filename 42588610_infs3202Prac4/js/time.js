function title_time(time, pagetitle) {
	hr = Math.floor(time / 3600);
	time = time % 3600;
	min = Math.floor(time / 60);
	time = time % 60;
	sec = time;
	sec--;
    // Countdown minutes
	if (sec < 0) {
		if ((min > 0) || (hr > 0)) {
			sec = 59;
			min--;
		}
	}
    // Countdown hours
	if (min < 0) {
		if (hr > 0) {
			min = 59;
			hr--;
		}
	}
	sec_remaining = sec + (min * 60) + (hr * 3600);
	if (sec_remaining > 0) {
		clocktext = " - Time Out " +  hr + ":" + ((min < 10) ? "0" : "") + min + ":" + ((sec < 10) ? "0" : "") + sec;
		top.document.title = pagetitle + clocktext;
	}
	else {
		top.document.title = pagetitle + ' - Timed Out';
	}
	setTimeout("title_time(" + sec_remaining + ",'" + pagetitle + "')", 1000);
}