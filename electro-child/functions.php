<?php
/**
 * Electro Child
 *
 * @package electro-child
 */

/**
 * Include all your custom code here
 */


/* canonicalを削除(オリジナルで追記するため) */
remove_action('wp_head', 'rel_canonical');
add_filter( 'wpseo_canonical', '__return_false' );

add_action('wp_head', function() {

  /* 404なら何も出力しない */
  if((is_404())){
    return;
  }

  $target_url = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

  /* note：ここはURL取得のためだけにget_the_permalink()でもいいんじゃないか、と思う。 */
  $us_url = "japee.tokyo/";
  //$us_url = "testjp.local/"; // テスト用
  $ch_url   = $us_url . "zh-hans-cn/"; // 中国サイト
  $engb_url = $us_url . "en-gb/";      // イギリスサイト
  $au_url   = $us_url . "au/";         // オーストラリア

  // 中国
  if(strpos($target_url,$ch_url) !== false){
      $canonical_alternate_tag =
        " <link rel=\"canonical\" href=" . $target_url. ">"
      . " <link rel=\"alternate\" href=" . str_replace($ch_url,$us_url  ,$target_url) . " hreflang=\"en\" />"        // en          英語
      . " <link rel=\"alternate\" href=" . str_replace($ch_url,$engb_url,$target_url) . " hreflang=\"en-gb\" />"     // en-gb       イギリス
      . " <link rel=\"alternate\" href=" . str_replace($ch_url,$au_url  ,$target_url) . " hreflang=\"au\" />"        // au          オーストラリア
      . " <link rel=\"alternate\" href=" . $target_url . " hreflang=\"zh-Hant-CN\" />"                               // zh-Hant-CN  中国語
      . " <link rel=\"alternate\" hreflang=\"x-default\" href=" . str_replace($ch_url,$us_url ,$target_url) . " />"  // x-default は常にen英語を表示
      ;
  }
  // en-gb イギリス
  // イギリスのcanonicalは英語サイトを表示する。
  else if(strpos($target_url,$engb_url) !== false){
      $canonical_alternate_tag =
        " <link rel=\"canonical\" href=" . str_replace($engb_url,$us_url  ,$target_url) . " />"
//    . " <link rel=\"alternate\" href=" . str_replace($engb_url,$us_url  ,$target_url) . " hreflang=\"en\" />"         // en         英語
      . " <link rel=\"alternate\" href=" . $target_url . " hreflang=\"en-gb\" />"                                       // en-gb      イギリス
      . " <link rel=\"alternate\" href=" . str_replace($engb_url,$au_url  ,$target_url) . " hreflang=\"au\" />"         // au         オーストラリア
      . " <link rel=\"alternate\" href=" . str_replace($engb_url,$ch_url  ,$target_url) . " hreflang=\"zh-Hant-CN\" />" // zh-Hant-CN 中国語
      . " <link rel=\"alternate\" hreflang=\"x-default\" href=" . str_replace($engb_url,$us_url  ,$target_url) . " />"  // x-default は常にen英語を表示
      ;
  }
  // オーストラリア
  else if(strpos($target_url,$au_url) !== false){
      $canonical_alternate_tag =
        " <link rel=\"canonical\" href=" . $target_url. ">"
      . " <link rel=\"alternate\" href=" . str_replace($au_url,$us_url  ,$target_url) . " hreflang=\"en\" />"         // en          英語
      . " <link rel=\"alternate\" href=" . $target_url . " hreflang=\"au\" />"                                        // au         オーストラリア
      . " <link rel=\"alternate\" href=" . str_replace($au_url,$engb_url,$target_url) . " hreflang=\"en-gb\" />"      // en-gb      イギリス
      . " <link rel=\"alternate\" href=" . str_replace($au_url,$ch_url  ,$target_url) . " hreflang=\"zh-Hant-CN\" />" // zh-Hant-CN 中国語
      . " <link rel=\"alternate\" hreflang=\"x-default\" href=" . str_replace($au_url,$us_url  ,$target_url) . " />"  // x-default は常にen英語を表示
      ;
  }
  else{
      /* デフォルト us */
      $canonical_alternate_tag = 
        " <link rel=\"canonical\" href=" . $target_url . ">"
      . " <link rel=\"alternate\" href=" . $target_url . " hreflang=\"en\" />"                                        // en         英語
      . " <link rel=\"alternate\" href=" . str_replace($us_url,$ch_url  ,$target_url) . " hreflang=\"zh-Hant-CN\" />" // zh-Hant-CN 中国語
      . " <link rel=\"alternate\" href=" . str_replace($us_url,$engb_url,$target_url) . " hreflang=\"en-gb\" />"      // en-gb      イギリス
      . " <link rel=\"alternate\" href=" . str_replace($us_url,$au_url  ,$target_url) . " hreflang=\"au\" />"         // au         オーストラリア
      . " <link rel=\"alternate\" hreflang=\"x-default\" href=" . $target_url . " />"                                 // x-default は常にen英語を表示
       ;
  }

  echo $canonical_alternate_tag;
}
);


function redirect_404() {
  $url = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
  $is_error_url = FALSE;

  // クッソ簡単な方法だが、product-categoryで同じカテゴリ属性が複数あれば404を返す、というのを作った。
  // これで大部分はエラーにできるのではなかろうか。
       if(substr_count($url,'product-category/'      ) > 1){ $is_error_url = TRUE; }
  else if(substr_count($url,'japanesefoods/'         ) > 1){ $is_error_url = TRUE; }
  else if(substr_count($url,'confectionery/'         ) > 1){ $is_error_url = TRUE; }
  else if(substr_count($url,'snack/'                 ) > 1){ $is_error_url = TRUE; }
  else if(substr_count($url,'beauty/'                ) > 1){ $is_error_url = TRUE; }
  else if(substr_count($url,'cleansing/'             ) > 1){ $is_error_url = TRUE; }
  else if(substr_count($url,'cleansing-oils/'        ) > 1){ $is_error_url = TRUE; }
  else if(substr_count($url,'cleansing-oils-refill/' ) > 1){ $is_error_url = TRUE; }

   // 404にしたい
  if($is_error_url == TRUE){
    //is_404()が効かないので強制的にフラグを付加する
    //$wp_query->is_404 = 1;
    //$wp_queryをdumpしてフラグがたっていたものはリセット
    //$wp_query->is_singular = '';
    //$wp_query->is_page = '';
    get_template_part('404');
    // exit()しないと余計なことするときある
    exit();
  }
}

add_action('template_redirect', 'redirect_404');