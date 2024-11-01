<?php

function getPostCodeType() {
  return array('card1','card2','list1');
}

function postCode($type, $class, $data) {
  $return_string = '';
  switch ($type) {
    case 'blog':
      $return_string .= blog($class, $data);
      break;
    case 'news':
      $return_string .= news($class, $data);
      break;
    default:
      $return_string .= 'ショートコードがありません。';
      break;
  }
  
  return $return_string;
}

function blog($class, $data){
  $return_string = '';
  // category
  // if ( ! empty( $data['categories'] ) ) {
  //   echo '<a href="' . esc_url( get_category_link( $data['categories'][0]->term_id ) ) . '">' . esc_html( $data['categories'][0]->name ) . '</a>';
  // }

  $return_string .= '<div class="tmax-blog__col">';
  $return_string .= '<div class="tmax-blog">';
    $return_string .= '<div class="tmax-blog__thumbnail">';
      $return_string .= '<a href="' . $data['link'] . '"><img class="tmax-blog__thumbnail-img" src="' . $data['thumbnail_url'] . '" alt=""></a>';
    $return_string .= '</div>';
    $return_string .= '<div class="tmax-blog__body">';
      $return_string .= '<ul class="tmax-blog__meta">';
        $return_string .= '<li class="tmax-blog__meta-date">' . $data['date'] . '</li>';
      $return_string .= '</ul>';
      $return_string .= '<h3 class="tmax-blog__title"><a href="' . $data['link'] . '">' . $data['title'] . '</a></h3>';
      $return_string .= '<p class="tmax-blog__text">' . $data['excerpt'] . '</p>';
      $return_string .= '<a class="tmax-blog__more" href="' . $data['link'] . '">Read More</a>';
    $return_string .= '</div>';
  $return_string .= '</div>';
  $return_string .= '</div>';


  return $return_string;
}

function card2($class, $data){
  $return_str = '';
  // category
  // if ( ! empty( $data['categories'] ) ) {
  //   echo '<a href="' . esc_url( get_category_link( $data['categories'][0]->term_id ) ) . '">' . esc_html( $data['categories'][0]->name ) . '</a>';
  // }

  
  $return_str .= '<div class="'.$class.' mb-6"><div class="card-post">';

    $return_str .= '<div class="card-post__image"><a href="'.$data['link'].'"><img class="img-fluid w-100" src="'.$data['thumbnail'].'" alt="'.$data['title'].'"></a></div>';

    $return_str .= '<div class="card-post__body">';
      $return_str .= '<h4 class="fz-4 mt-3">';
        $return_str .= '<a class="fz-4 fw-bold" href="'.$data['link'].'">'.$data['title'].'</a>';
      $return_str .= '</h4>';
      if ( ! empty( $data['tags'] ) ) {
        $return_str .= '<ul class="nav mt-3 lh-1">';
        foreach($data['tags'] as $tag) {
          $return_str .= '<li class="pr-2"><a class="fz-2 py-1 px-2 bgc-light" href="' . esc_url( get_tag_link( $tag->term_id ) ) . '">'.$tag->name.'</a></li>';
        }
        $return_str .= '</ul>';
      }
      $return_str .= '<ul class="nav mt-2 lh-1"><li class="fz-2 c-gray">'.$data['date'].'</li></ul>';
    $return_str .= '</div>';

  $return_str .= '</div></div>';


  return $return_str;
}

function news($class, $data){
  $return_string = '';
  
  $return_string .= '<li class="tmax-news__item">';
    $return_string .= '<span class="tmax-news__item-date">' . $data['date'] . '</span>';

    if ( ! empty( $data['terms'] ) ) {
      $return_string .= '<a class="tmax-news__item-category" href="' . esc_url( get_category_link( $data['terms'][0]->term_id ) ) . '">' . esc_html( $data['terms'][0]->name ) . '</a>';
    }

    $return_string .= '<a class="tmax-news__item-link" href="' . $data['link'] . '">' . $data['title'] . '</a>';
  $return_string .= '</li>';


  return $return_string;
}