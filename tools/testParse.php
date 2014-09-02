<?php

$data=  null;
exec('../tools/citationParse "Walboomers JM, Jacobs MV, Manos MM, Bosch FX, Kummer JA, Shah KV, Snijders PJ, Peto J, Meijer CJ, Munoz N (1999)Human papillomavirus is a necessary cause of invasive cervical cancer worldwide. The Journal of pathology 189:12-19."',$data,$retval);
if(!isset($data[0])){
	echo "error";
}
print_r(json_decode($data[0]));
