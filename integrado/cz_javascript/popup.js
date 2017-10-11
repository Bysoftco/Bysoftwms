var timerID=""
var newwin=""
var popup=""
var waiting=0
var popupdelay=200

function startHelp(str) {
   window.status=""
   var str1="showHelp('" + str + "')"
   timerID=setTimeout(str1, 200)
   waiting=1
}

function showHelp(str) {
   if (newwin=="" || newwin=="null" || newwin.name=="" || newwin.name=="null")
      newwin = window.open("", "subWindow", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=260,height=280")
   else
      newwin.focus()

   newwin.document.open()
   var to_page = "<HTML><HEAD></HEAD><BODY BGCOLOR=#FFF7D7><P>"
   to_page += str
   to_page +="</P></BODY></HTML>\n"
   newwin.document.write(to_page)
   newwin.document.close()
   waiting=0
}

function clearHelp() {
   waiting=0
   clearTimeout(timerID)
   if (newwin!="" && newwin!="null" && newwin.name!="null") {
      newwin.close()
      newwin=""
   }
}
function popupFreeMain(URL, w, h) {
	
	var winLeft = (screen.width - w) / 2;
	var winUp = (screen.height - h) / 2;
	var windowprops = "status=yes,location=no,scrollbars=no,menubar=no,toolbar=no,resizable=yes" +
				  ",left=" + winLeft + ",top=" + winUp + ",width=" + w + ",height=" + h;

	popup = window.open(URL,"",windowprops);
	popup.focus();
}

function popupPage(URL, l, t, w, h) {
var windowprops = "location=no,scrollbars=yes,menubar=no,toolbar=no,resizable=yes" +
",left=" + l + ",top=" + t + ",width=" + w + ",height=" + h;
	popup = window.open(URL,"winPopup",windowprops);
	popup.focus()
}

function popupMain(URL, w, h) {
var winLeft = (screen.width - w) / 2;
var winUp = (screen.height - h) / 2;
var windowprops = "status=yes,location=no,scrollbars=no,menubar=no,toolbar=no,resizable=yes" +
				  ",left=" + winLeft + ",top=" + winUp + ",width=" + w + ",height=" + h;
	popup = window.open(URL,"ecrmWin",windowprops);
	popup.focus()
}

function fullScreen(URL) {
  window.open(URL,'winFull','width='+screen.width+',height='+screen.height+',top=0,left=0');
}

function popupModalDialog(URL, vArguments, w, h) {
	var windowprops = "help:no;resizable:no;status:no;" + "dialogWidth:" + w + "px;dialogHeight:" + h +"px";
	returnValue = 0;
	returnValue = window.showModalDialog(URL,vArguments,windowprops);
	if (returnValue != undefined) {
	vArguments.value = returnValue;
	}
}

function popupPageMain(URL, w, h) {
var winLeft = (screen.width - w) / 2;
var winUp = (screen.height - h) / 2;
var windowprops = "location=no,scrollbars=no,menubar=no,toolbar=no,resizable=no" +
",left=" + winLeft + ",top=" + winUp + ",width=" + w + ",height=" + h;
	popup = window.open(URL,"winPopupMain",windowprops);
	popup.focus()
}