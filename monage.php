<?php
/*
Plugin Name: monage
Description: Let's make monage (giving monacoin) to wordpress blog more easier!
Version: 0.04
Author: raspi0124
Author URI: https://raspi-diary.com/
License: GPLv3
*/
/*  Copyright 2017 raspi0124 (email : admin@raspi-diary.com)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
Copyright  2017  raspi0124
このプログラムはフリーソフトウェアです。あなたはこれを、フリーソフトウェ
ア財団によって発行された GNU 一般公衆利用許諾契約書(バージョン2か、希
望によってはそれ以降のバージョンのうちどれか)の定める条件の下で再頒布
または改変することができます。
このプログラムは有用であることを願って頒布されますが、*全くの無保証* 
です。商業可能性の保証や特定の目的への適合性は、言外に示されたものも含
め全く存在しません。詳しくはGNU 一般公衆利用許諾契約書をご覧ください。
 
あなたはこのプログラムと共に、GNU 一般公衆利用許諾契約書の複製物を一部
受け取ったはずです。もし受け取っていなければ、フリーソフトウェア財団ま
で請求してください(宛先は the Free Software Foundation, Inc., 59
Temple Place, Suite 330, Boston, MA 02111-1307 USA)。
    This software includes the work that is distributed in the Apache License 2.0
*/
function monage_post_twitterid() {
    return get_option( 'monage_twitter_account' );
}

function monage_img_lacation() {
    return plugins_url( 'monage.png', __FILE__ );
}

add_shortcode('monage_twid', 'monage_post_twitterid');
add_shortcode('monage_imgloc', 'monage_img_lacation');
//add option for wordpress
    function monage_addfield() {
    add_settings_field( 'twitter', 'モナコインを投げる先のTwitterのIDをお願いします。', 'monage_twitter_field', 'general', 'default', array( 'label_for' => 'monage_twitter_account' ) );
}
add_action( 'admin_init', 'monage_addfield' );
 
function monage_twitter_field( $args ) {
    $monage_twitter_account = get_option( 'monage_twitter_account' );
?>
    @<input type="text" name="monage_twitter_account" id="monage_twitter_account" size="30" value="<?php echo esc_html( $monage_twitter_account ); ?>" />
<?php
}
function monage_post_amount() {
    return get_option( 'monage_amount' );
}
add_shortcode('monage_amount', 'monage_post_amount');
function monage_add_option() {
    register_setting( 'general', 'monage_twitter_account' );
}
add_filter( 'admin_init', 'monage_add_option' );
//add after post
function monage_addafterpost($monage_content) {
 
$monage_bottom = <<< sentence
<center><a href="https://twitter.com/share?text=@tipmona%20tip%20@[monage_twid]%200.114114%20Monaを送ります">
<img src="[monage_imgloc]" alt="Monacoinを投げる" rel="nofollow" class="monage_image">
</a> <br><a href="https://monappy.jp/memo_logs/view/monappy/123" target="_blank">モナゲ(tipmona)ってなに？</a><br>
<a href="http://dic.nicovideo.jp/a/monacoin" rel="nofollow" target="_blank">そもそもMonacoinってなに？</a><style>
.monage_image {
    width: 300px;  /* 横幅を300pxに */
}
</style>
sentence;
 
    if(!is_feed() && !is_home()) {
        $monage_content .= $monage_bottom;
    }
    return $monage_content;
}
add_filter('the_content', 'monage_addafterpost');
