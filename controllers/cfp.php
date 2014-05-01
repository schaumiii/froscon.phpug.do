<?php

?>
<h3>Call For Papers</h3>
<p>We want to nerd out in our room. We want you to propose talks on nerdy, crazy stuff you did with PHP. Not the common conference talk, but let us hear about the crazy shit you are not supposed to tell anyone. Wrote a distributed raytracer, which generates images based on cosmic radiation only using functional paradigms in PHP? You are right on.</p>
<h4>Facts:</h4>
<ul>
    <li>You get free entry to the conference.</li>
    <li>There will be food and drinks for you, for free.</li>
    <li>We will not be able to cover travel or accomodation.</li>
</ul>
<h4>Your talk</h4>
<p>When selecting the talks we will <strong>not</strong> know who proposed the talk.</p>
<form method="POST">
    <fieldset>
        <label for="name">Full Name</label>
        <input type="text" id="name" placeholder="Your full name">

        <label for="email">E-Mail</label>
        <input type="text" id="email" placeholder="you@example.com">

        <span class="checkbox">
            <input type="checkbox" name="first" id="first">
            <label for="first">Check this, if you are a first-time speaker – we can then provide you with additional mentoring to get your talk right.</label>
        </span>

        <label for="title">Talk Title</label>
        <input type="text" id="title" placeholder="Talk title">

        <label for="abstract">Abstract (will appear on website)</label>
        <textarea name="abstract" id="abstract"></textarea>

        <label for="notes">Notes (will not appear on website)</label>
        <textarea name="notes" id="abstract"></textarea>

        <input type="submit" value="Submit talk" class="button">
    </fieldset>
</form>
