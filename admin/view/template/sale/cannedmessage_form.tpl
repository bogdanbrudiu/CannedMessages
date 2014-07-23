<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/cannedmessage.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
       
            <table class="form">
              <tr>
                <td><span class="required">*</span> <?php echo $entry_title; ?></td>
                <td><input type="text" name="cannedmessage_title" size="100" value="<?php echo isset($cannedmessage_title) ? $cannedmessage_title : ''; ?>" />
                  <?php if (isset($error_title)) { ?>
                  <span class="error"><?php echo $error_title; ?></span>
                  <?php } ?></td>

              </tr>
              <tr>
                <td><span class="required">*</span> <?php echo $entry_description; ?></td>
                <td><textarea name="cannedmessage_description" id="description" rows="15" size="200"><?php echo isset($cannedmessage_description) ? $cannedmessage_description : ''; ?></textarea>
                  <?php if (isset($error_description)) { ?>
                  <span class="error"><?php echo $error_description; ?></span>
                  <?php } ?></td>
              </tr>
  <tr>

              <td><?php echo $entry_status; ?></td>

              <td><select name="status">

                  <?php if ($status) { ?>

                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

                  <option value="0"><?php echo $text_disabled; ?></option>

                  <?php } else { ?>

                  <option value="1"><?php echo $text_enabled; ?></option>

                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

                  <?php } ?>

                </select></td>

            </tr>

            <tr>

              <td><?php echo $entry_sort_order; ?></td>

              <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>

            </tr>


            </table>
         
        </div>
      </form>
    </div>
  </div>
</div>

<?php echo $footer; ?>