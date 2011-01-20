
<table class="box">
  <tr>
    <th>Attachments</th>
  </tr>
  <tr>
    <td>
      <table class='sortable list'>
        <tr>
          <th>File</th>
          <th class="nobr">Date Modified</th>
          <th>Size</th>
          <th align="right"></th>
        </tr>
      {$attachments}
      {if $show_buttons}
        <tr>
          <td colspan="1" class="left" style="padding:5px;">
            {$bottom_button}
          </td>
          <td colspan="3" class="right nobr" style="padding:5px;">
            <form enctype="multipart/form-data" action="{$entity_url}" method="post">
            <input type="hidden" name="MAX_FILE_SIZE" value="10000000">
            <input type="hidden" name="{$entity_key_name}" value="{$entity_key_value}">
            <input type="file" name="attachment" />
            <input type="submit" value="Upload Attachment" name="save_attachment">
            <input type="hidden" name="sbs_link" value="attachments">
            </form>
          </td>
        </tr>
      {/}
      </table>
    </td>
  </tr>
</table>

