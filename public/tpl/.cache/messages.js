/*TMODJS:{"version":1,"md5":"3c36d39fa28f6827bad65482c236f104"}*/
template('messages',function($data,$filename
/**/) {
'use strict';var $utils=this,$helpers=$utils.$helpers,$each=$utils.$each,messages=$data.messages,$value=$data.$value,$index=$data.$index,$escape=$utils.$escape,$out='';$out+='<h1>Messages</h1> type: <ul class="nav nav-pills"> <li role="presentation" class="js-msg-type active"><a href="javascript:void(0)" class="" data-type=\'all\'>all</a></li> <li role="presentation" class="js-msg-type"><a href="javascript:void(0)" class="" data-type=\'private\'>private</a></li> <li role="presentation" class="js-msg-type"><a href="javascript:void(0)" class="" data-type=\'friend\'>friend</a></li> <li role="presentation" class="js-msg-type"><a href="javascript:void(0)" class="" data-type=\'neighbor\'>neighbor</a></li> </ul> <table class=\'table\'> <thead> <tr> <th>title</th> <th>content</th> <th>from</th> <th>type</th> </tr> </thead> <tbody> ';
$each(messages,function($value,$index){
$out+=' <tr> <th>';
$out+=$escape($value.m_title);
$out+='</th> <th>';
$out+=$escape($value.m_content);
$out+='</th> <th>';
$out+=$escape($value.m_from.u_name);
$out+='</th> <th>';
$out+=$escape($value.m_type);
$out+='</th> </tr> ';
});
$out+=' </tbody> </table> <!-- <ul> <!-- ';
$each(messages,function($value,$index){
$out+=' <li> <p>title:';
$out+=$escape($value.m_title);
$out+='</p> <p>content: ';
$out+=$escape($value.m_content);
$out+='</p> <p>from: ';
$out+=$escape($value.m_from);
$out+='</p> <p>type: ';
$out+=$escape($value.m_type);
$out+='</p> </li> ';
});
$out+=' -->  <script src=\'./js/messages.js\'></script>';
return new String($out);
});