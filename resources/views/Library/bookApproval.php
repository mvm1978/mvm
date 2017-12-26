<?php

$user = trim($info['first_name'] . ' ' . $info['last_name']);

$comma = $user ? ',' : NULL; ?>

<h3>Hello<?php echo $comma . ' ' . $user; ?></h3>
<p>Thank you for uploading a book</p>
<p>The book will be added to the library once it is approved by administrators</p>