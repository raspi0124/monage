<?php
/*
Plugin Name: monage
Description: Let's make monage (giving monacoin) to wordpress blog more easier!
Version: 1.0
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


function monage_picwi() {
    return get_option( 'monage_picture_width' );
}

add_shortcode('monage_twid', 'monage_post_twitterid');
add_shortcode('monage_imgloc', 'monage_img_lacation');
add_shortcode('monage_picwi', 'monage_picwi');


// 管理メニューにフックを登録
add_action('admin_menu', 'monage_add_pages');

// メニューを追加する
function monage_add_pages()
{
    // プラグインのスラグ名はユニークならなんでも良い
    // /plugin/monage-test/monage-test.phpに置いているので
    $monage_plugin_slug = plugin_basename(__FILE__);


    // 既存の「設定」メニューにサブメニューを追加:
    add_options_page('monage', 'monage',
        'manage_options',
        'monage-options-submenu',
        'monage_options_page');
}

// メニューがクリックされた時にコンテンツ部に表示する内容
// メニューで表示されるページの内容を返す関数
function monage_options_page() {
    // POSTデータがあれば設定を更新
    if (isset($_POST['monage_twitter_account'])) {
        // POSTデータの'"などがエスケープされるのでwp_unslashで戻して保存
        update_option('monage_twitter_account', wp_unslash($_POST['monage_twitter_account']));
         update_option('monage_picture_width', wp_unslash($_POST['monage_picture_width']));
        update_option('monage_payway', $_POST['monage_payway']);
        // チェックボックスはチェックされないとキーも受け取れないので、ない時は0にする
        $monage_use_cdn = isset($_POST['monage_use_cdn']) ? 1 : 0;
        update_option('monage_use_cdn', $monage_use_cdn);
    }

$monage_usecdn = "1";
$monage_cdnimgloc = "https://cdn.rawgit.com/raspi0124/monage/5fc30ee7/monage.png";


if ($monage_usecdn == "1") {
    function monage_img_lacation() {
    return $monage_cdnimgloc;
}
}

else{   
    function monage_img_lacation() {
    return plugins_url( 'monage.png', __FILE__ );
}
}


?>
<div class="wrap">
<h2>Monage 設定画面</h2>
<?php
    // 更新完了を通知
    if (isset($_POST['monage_twitter_account'])) {
        echo '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
            <p><strong>設定を保存しました。</strong></p></div>';
    }
?>

<form method="post" action="">
<table class="form-table">
    <tr>
        <th scope="row"><label for="monage_twitter_account">モナコインを投げる先のTwitterのIDをお願いします。</label></th>
        <td>@<input name="monage_twitter_account" type="text" id="monage_twitter_account" value="<?php form_option('monage_twitter_account'); ?>" class="regular-text" /></td>
    </tr>

    <tr>
        <th scope="row"><label for="monage_picture_width">画像の幅</label></th>
        <td><input name="monage_picture_width" type="text" id="monage_picture_width" value="<?php form_option('monage_picture_width'); ?>" class="regular-text" /></td>
    </tr>

    <tr>
        <th scope="row">投げmonaの手段 （現在絶賛プラグイン構築中です。。待っててくださいな。）</th>
        <td><p><label><input name="monage_payway" type="radio" value="0" disabled='disabled' <?php checked( 0, get_option( 'monage_payway' ) ); ?>    />tipmonaを投げ銭の手段として利用する </label><br />
                <label><input name="monage_payway" type="radio" value="1" disabled='disabled' <?php checked( 1, get_option( 'monage_payway' ) ); ?> />askmonaのapiを使用して投げ銭してもらう</label></p>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="monage_use_cdn">画像の読み込みにCDNを使用(ベータ)</label></th>
        <td><label><input name="monage_use_cdn" type="checkbox" id="monage_use_cdn" value="1" <?php checked( 1, get_option('monage_use_cdn')); ?> /> チェック</label></td>
    </tr>

</table>
<?php submit_button(); ?>
</form>
</div>
<?php
}


//add after post
function monage_addafterpost($monage_content) {
 
$monage_bottom = <<< sentence
<center><a href="https://twitter.com/share?text=@tipmona%20tip%20@[monage_twid]%200.114114%20Monaを送ります">
<img src="[monage_imgloc]" alt="Monacoinを投げる" rel="nofollow" class="monage_image">
</a> <br><a href="https://monappy.jp/memo_logs/view/monappy/123" target="_blank">モナゲ(tipmona)ってなに？</a><br>
<a href="http://dic.nicovideo.jp/a/monacoin" rel="nofollow" target="_blank">そもそもMonacoinってなに？</a></center><style>
.monage_image {
    width: [monage_picwi]px; 
}
</style>

sentence;
 
    if(!is_feed() && !is_home()) {
        $monage_content .= $monage_bottom;
    }
    return $monage_content;
}
add_filter('the_content', 'monage_addafterpost');
