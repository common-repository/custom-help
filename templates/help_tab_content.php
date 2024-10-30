<div id="custom-help" data-show="content">
  <div id="custom-help-header">
    <figure>
      <img src="<?php echo esc_attr($template_vars['PLUGIN_LOGO']); ?>" alt="<?php echo esc_attr($template_vars['PLUGIN_NAME']); ?>" />
    </figure>
    <div>
      <div data-type="content">
        <?php if ($template_vars['ID']) { ?>
          <a href="#" class="custom-help-edit-documentation"><?php echo esc_html($template_vars['EDIT_DOCUMENTATION']); ?></a>
        <?php } else { ?>
          <a href="#" class="custom-help-edit-documentation"><?php echo esc_html($template_vars['CREATE_DOCUMENTATION']); ?></a>
        <?php } ?>
      </div>
      <div data-type="form">
        <a href="#" class="custom-help-show-documentation"><?php echo esc_html($template_vars['SHOW_DOCUMENTATION']); ?></a>
      </div>
    </div>
  </div>
  <div>
    <div id="custom-help-form" data-type="form" data-loading="false">
      <div class="custom-help-form-loading"><span></span></div>
      <form>
        <input type="hidden" name="id" value="<?php echo esc_attr($template_vars['ID']); ?>">
        <input type="hidden" name="filename" value="<?php echo esc_attr($template_vars['FILENAME']); ?>">
        <input type="hidden" name="page" value="<?php echo esc_attr($template_vars['PAGE']); ?>">
        <input type="hidden" name="post_type" value="<?php echo esc_attr($template_vars['POST_TYPE']); ?>">
        <div class="custom-help-form-field">
          <select name="is_markdown">
            <?php if ($template_vars['IS_MARKDOWN']) { ?>
              <option value="true" selected><?php echo esc_html($template_vars['USE_MARKDOWN']); ?></option>
              <option value="false"><?php echo esc_html($template_vars['DONT_USE_MARKDOWN']); ?></option>
            <?php } else { ?>
              <option value="true"><?php echo esc_html($template_vars['USE_MARKDOWN']); ?></option>
              <option value="false" selected><?php echo esc_html($template_vars['DONT_USE_MARKDOWN']); ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="custom-help-form-field">
          <textarea name="content"><?php echo esc_textarea($template_vars['CONTENT']); ?></textarea>
        </div>
        <div class="custom-help-form-buttons">
          <a href="#" id="custom-help-form-submit"><?php echo esc_html($template_vars['SUBMIT_FORM']); ?></a>
          <a href="#" class="custom-help-show-documentation"><?php echo esc_html($template_vars['SHOW_DOCUMENTATION']); ?></a>
        </div>
      </form>
    </div>
    <div id="custom-help-content" data-type="content">
      <?php if ($template_vars['ID']) { ?>
        <?php if ($template_vars['CONTENT_PARSED']) { ?>
          <?php echo wp_kses_post($template_vars['CONTENT_PARSED']); ?>
        <?php } else { ?>
          <div class="custom-help-content-error">
            <span><?php echo esc_html($template_vars['DOCUMENTATION_EMPTY']); ?></span>
            <a href="#" class="custom-help-edit-documentation"><?php echo esc_html($template_vars['EDIT_DOCUMENTATION']); ?></a>
          </div>
        <?php } ?>
      <?php } else { ?>
        <div class="custom-help-content-error">
          <span><?php echo esc_html($template_vars['DOCUMENTATION_NOT_FOUND']); ?></span>
          <a href="#" class="custom-help-edit-documentation"><?php echo esc_html($template_vars['CREATE_DOCUMENTATION']); ?></a>
        </div>
      <?php } ?>
    </div>
    <div id="custom-help-content-last-edition" data-hidden="<?php echo esc_attr($template_vars['HIDE_LAST_EDITION']); ?>">
      <?php echo wp_kses_post($template_vars['LAST_EDITION']); ?>
    </div>
  </div>
</div>