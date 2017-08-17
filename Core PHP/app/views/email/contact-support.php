<style>
h1 {
  font-family: arial;
  font-size: 28px;
  font-weight: normal;
  line-height: 20px;
}
    p {
  font-family: Arial;
  font-size: 15px;
  line-height: 20px;
}
    h3 {
  font-family: arial;
  line-height: 20px;
  font-size: 20px;
}
    h6 {
  font-family: arial;
  font-size: 18px;
  font-weight: normal;
  line-height: 20px;
  margin: 0;
}
    li {
  font-family: arial;
  font-size: 15px;
  line-height: 25px;
}
    img {
  float: left;
  width: 16%;
}
    ul {
  list-style: outside none none;
  padding: 0;
}
</style>

<section>
    
<h3>You have received a new support query:-</h3>
<ul>
    <li>Name: <?= $user['first_name'] . ' ' . $user['last_name']; ?></li>
    <li>Email: <?= $user['email']; ?></li>
    <li>Account Type: <?= (empty($user['instagram_username'])) ? 'Guest' : ''; ?><?= ($user['is_active'] == '1') ? 'Customer' : ''; ?><?= ($user['is_merchant'] == '1') ? '/Merchant' : ''; ?></li>
    <li>Date submitted: <?= gmdate('d-m-Y H:i:s T'); ?></li>
    <li>Message: <?= $data['message']; ?></li>
</ul> 
</section>