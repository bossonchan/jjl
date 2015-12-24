/*TMODJS:{"version":1,"md5":"f8e7ed1e92a5402856fff191f38ea287"}*/
template('blocks',function($data,$filename
/**/) {
'use strict';var $utils=this,$helpers=$utils.$helpers,blocks=$data.blocks,$each=$utils.$each,$value=$data.$value,$index=$data.$index,$escape=$utils.$escape,$out='';$out+='<h1>blocks</h1> <ul> ';
if(blocks.length){
$out+=' ';
$each(blocks,function($value,$index){
$out+=' <li class=\'js-blocks\'> <span class="b_name" data-id="';
$out+=$escape($value.block_id);
$out+='">';
$out+=$escape($value.b_name);
$out+='</span> <button class=\'btn btn-xs btn-primary js-join-block\' data-id="';
$out+=$escape($value.block_id);
$out+='">join</button> </li> ';
});
$out+=' ';
}else{
$out+=' <h2>no blocks</h2> ';
}
$out+=' </ul> <button class="btn btn-s btn-week js-back-hoods">back</button> <script src=\'./js/hoods.js\'></script> ';
return new String($out);
});