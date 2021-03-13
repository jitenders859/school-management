  <div class="w3-half w3-container">
    <div class="w3-card-4 edit-block" style="margin-top: 1em;">
      <div class="w3-container primary">
        <h4>Customer Details</h4>
      </div>
      <div class="w3-container w3-padding edit-block-content">
        <table class="w3-table w3-striped">
          <tr>
            <td>First Name</td>
            <td><?= $first_name ?></td>
          </tr>
          <tr>
            <td>Last Name</td>
            <td><?= $last_name ?></td>
          </tr>
          <tr>
            <td>Username</td>
            <td><?= $username ?></td>
          </tr>
          <tr>
            <td>Telephone Number</td>
            <td><?= $telephone_number ?></td>
          </tr>
          <tr>
            <td>Email Address</td>
            <td><?= $email ?></td>
          </tr>
          <tr>
            <td style="vertical-align: top;">Customer Address</td>
            <td style="vertical-align: top;"><?= $customer_address ?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>