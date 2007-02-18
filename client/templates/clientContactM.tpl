  {$table_box}
    <tr>
      <th>Client Contacts</th>
      <th class="right">{get_expand_link("id_new_client_contact")}</th>
    </tr>
    <tr>
      <td colspan="2">
        <form action="{$url_alloc_client}" method="post">
        <input type="hidden" name="clientContactID" value="{$clientContact_clientContactID}">
        <input type="hidden" name="clientID" value="{$clientContact_clientID}">
        
        <div class="{$class_new_client_contact}" id="id_new_client_contact">
        <table width="100%">
          <tr>
            <td>Name</td> <td><input type="text" name="clientContactName" value="{$clientContact_clientContactName}"></td>
            <td>Email Address</td> <td><input type="text" name="clientContactEmail" value="{$clientContact_clientContactEmail}"></td>
            <td>Info</td>
            <td rowspan="4"><textarea name="clientContactOther" cols="40" rows="6" wrap="virtual">{$clientContact_clientContactOther}</textarea></td>
          </tr>
          <tr>
            <td class="nobr">Street Address</td> <td><input type="text" name="clientContactStreetAddress" value="{$clientContact_clientContactStreetAddress}"></td>
            <td>Phone Number</td> <td><input type="text" name="clientContactPhone" value="{$clientContact_clientContactPhone}"></td>
            <td></td><td></td>
          </tr>
          <tr>
            <td>Suburb</td> <td><input type="text" name="clientContactSuburb" value="{$clientContact_clientContactSuburb}"></td>
            <td>Mobile Number</td> <td><input type="text" name="clientContactMobile" value="{$clientContact_clientContactMobile}"></td>
            <td></td><td></td>
          </tr>
          <tr>
            <td>Postcode</td> <td><input type="text" name="clientContactPostcode" value="{$clientContact_clientContactPostcode}"></td>
            <td>Fax Number</td> <td><input type="text" name="clientContactFax" value="{$clientContact_clientContactFax}"></td>
            <td></td><td></td>
          </tr>
          <tr>
            <td>State</td> <td><input type="text" name="clientContactState" value="{$clientContact_clientContactState}"></td>
            <td class="nobr">Primary Contact</td><td><input type="checkbox" name="clientPrimaryContactID" value="{$clientContact_clientContactID}"{$clientPrimaryContactID_checked}></td>
            <td></td><td align="right">{$clientContactItem_buttons}</td>
          </tr>
          <tr>
            <td colspan="6">&nbsp;</td>
          </tr>
        </table>
        </div>

        </form>

      </td>
    </tr>
    <tr>
      <td colspan="2">
        {$clientContacts}
      </td>
    </tr>
  </table>