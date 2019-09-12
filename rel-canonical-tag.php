<?php

namespace uptimizt;

/**
 * Description class
 */
class RelCanonicalTag {

  public static function init(){
      add_action( 'edit_terms', [__CLASS__, 'save_term_data'], 10, 2 );
      add_action('post_tag_edit_form', [__CLASS__, 'term_render_field'], 11, 2);
      add_action('category_edit_form', [__CLASS__, 'term_render_field'], 11, 2);

      add_filter('get_canonical_url', [__CLASS__, 'filter_canonical']);
      add_filter('the_seo_framework_rel_canonical_output', [__CLASS__, 'tsf_filter_canonical'], 11, 2);

  }

  /**
   * Description
   * apply_filters( 'get_canonical_url', string $canonical_url, WP_Post $post )

   */
  public static function tsf_filter_canonical($canonical_url, $id){

      if(is_tax() || is_category() || is_tag()){
          if($new_canonical_url = get_term_meta($id, 'term_canonical', true)){
              if (filter_var($new_canonical_url, FILTER_VALIDATE_URL) !== FALSE) {
                  $canonical_url = $new_canonical_url;
              }
          }
      }

      return $canonical_url;

  }

  public static function filter_canonical($canonical_url){


      return $canonical_url;
    // code
  }


    function save_term_data( $term_id, $taxonomy ){
        if(!$link = @$_REQUEST['term_canonical']){
            return;
        }

        update_term_meta($term_id, 'term_canonical', $link);
    }

    function term_render_field( $tag, $taxonomy ){
        //do something before update OR
        // echo 1111;
        $value = get_term_meta($tag->term_id, 'term_canonical', true);
        // var_dump($tag->term_id);

        ?>
        <table class="form-table" role="presentation">
		  <tbody>
            <tr class="form-field">
                <th scope="row" valign="top">
                    <label for="term_canonical">Canonical</label>
                </th>
                <td>
                    <input type="text" name="term_canonical" id="term_canonical" value="<?= $value ?>">
                </td>
            </tr>
		  </tbody>
        </table>

        <?php
    }

}

RelCanonicalTag::init();
