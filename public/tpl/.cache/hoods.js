/*TMODJS:{"version":1,"md5":"f4dbd7a92467293e29bc2a7338eddc8c"}*/
template('hoods',function($data,$filename
/**/) {
'use strict';var $utils=this,$helpers=$utils.$helpers,$each=$utils.$each,hoods=$data.hoods,$value=$data.$value,$index=$data.$index,$escape=$utils.$escape,$out='';$out+='<h1>hoods</h1> <ul> ';
$each(hoods,function($value,$index){
$out+=' <li class=\'js-hood\'><a href=\'javascript:void(0)\' data-id="';
$out+=$escape($value.hood_id);
$out+='">';
$out+=$escape($value.h_name);
$out+='</a></li> ';
});
$out+=' <script src=\'./js/hoods.js\'></script> </ul>';
return new String($out);
});