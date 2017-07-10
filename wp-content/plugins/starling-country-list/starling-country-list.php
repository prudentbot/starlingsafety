<?php
/*
  Plugin Name: Starling Country List
  Plugin URI: tylerbuilds.website
  Description: Generates Country List automatically- integrates with country search
  Version: 1.0
  Author: Tyler Smith
  Author URI: tylerbuilds.website
  License: GPL2
*/

function starling_list_array_sort($array, $on, $order=SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

function starling_country_list_shortcode($atts, $content = null){
  $pages = get_pages();
  $data = array();
  foreach($pages as $p){
    $safety_score = get_post_meta($p->ID, 'danger-level', true);

    if(!$safety_score)
      continue;

    $innerArray = array('title' => get_the_title($p), 'permalink' => get_permalink($p->ID));

    array_push($data, $innerArray);
  }
  $data = starling_list_array_sort($data, 'title', SORT_ASC);

  if (!$data)
    return;

  $html = '<hr />';
  $index = 0;
  while($index < sizeof($data)){
    $letter = substr ($data[$index]['title'] , 0, 1);
    $html .= '<h5><strong>' . $letter . '</strong></h5><ul>';

    //Regex to check for non-ascii characters.  Åland Islands causes some problems.
    while($index < sizeof($data) && (preg_match('/[^\x20-\x7f]/', substr($data[$index]['title'], 0, 1 )) || strcmp(substr($data[$index]['title'], 0, 1 ), $letter) == 0)){
      $html .= '<li><a href="' . $data[$index]['permalink'] . '"><strong>' . $data[$index]['title'] .'</strong></a></li>';
      $index++;
    }
    $html .= '</ul><a href="#top">Return to top</a><hr />';
  }
  return $html;
}
add_shortcode( 'starling_country_list', 'starling_country_list_shortcode' );

?>
