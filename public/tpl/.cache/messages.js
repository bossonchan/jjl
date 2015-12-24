/*TMODJS:{"version":1,"md5":"224b1bf9164ea3c142d282eba955a94e"}*/
template('messages',function($data,$filename
/**/) {
'use strict';var $utils=this,$helpers=$utils.$helpers,$each=$utils.$each,messages=$data.messages,$value=$data.$value,$index=$data.$index,$escape=$utils.$escape,$out='';$out+='<h1>Messages</h1> type: <ul class="nav nav-pills"> <li role="presentation" class="js-msg-type active"><a href="javascript:void(0)" class="" data-type=\'0\'>all</a></li> <li role="presentation" class="js-msg-type"><a href="javascript:void(0)" class="" data-type=\'1\'>private</a></li> <li role="presentation" class="js-msg-type"><a href="javascript:void(0)" class="" data-type=\'2\'>friend</a></li> <li role="presentation" class="js-msg-type"><a href="javascript:void(0)" class="" data-type=\'3\'>block</a></li> </ul> <ul> ';
$each(messages,function($value,$index){
$out+=' <li> <a href=\'javascript:void(0)\' data-id="';
$out+=$escape($value.id);
$out+='">';
$out+=$escape($value.title);
$out+='</a> <p>';
$out+=$escape($value.content);
$out+='</p> <p>';
$out+=$escape($value.from);
$out+='</p> </li> ';
});
$out+=' <script src=\'./js/messages.js\'></script> </ul>';
return new String($out);
});