<?php
include("config.php");
html_head("Search");

include("header.html");

print "<div class='content'>";

print '
<h2>Advanced Search</h2>
<form name="sermonsquery" action="search.php" method="get">
<table class="searchform" border="0">
<tr><th>Keyword</th><td><input type="text" size="40" name="keyword"></td></tr>
<tr><th>Title</th><td><input type="text" size="40" name="title"></td></tr>
<tr><th>Author</th><td><input type="text" size="40" name="author"></td></tr>
<tr><th>Sermon Date</th><td><input type="text" size="40" name="date"></td></tr>
<tr><th>Place of Publication</th><td><input type="text" size="40" name="place"></td></tr>
<tr><td></td><td><input type="submit" value="Submit"> <input type="reset" value="Reset"></td></tr>
</table>
</form>';

/*
print'
<hr width="60%" align="right">
<p><h4>Search tips:</h4>
<ul>
<li>Search terms are matched against <i>whole words</i>.<br>
For example, searching for <b>america</b> will not match <b>american</b>.</li>
<li>Multiple words are allowed.<br> 
For example, enter <b>civil war</b> or <b>New York</b> to match those words separately.<br>
Use the "Exact Phrase" option below for matching a phrase or a specified ordering of words</li>
<li>Asterisks may be used when using a part of of word or words for matches.<br>
For example, enter <b>resign*</b> to match <b>resign</b>, <b>resigned</b>, and
<b>resignation</b><br> or <b>*th Carolina</b> to match both
<b>North Carolina</b> and <b>South Carolina</b>.</li>
<li> Use several categories to narrow your search. For example, use author, keyword and<br>
title to match a particular sermon.
</ul>
</p>

<p>If you are interested in doing a more complex search, please
contact the <a href="mailto:beckcenter@emory.edu">Beck Center
Staff</a>.</p>; */

print '
<h2>Specialized Search</h2>
<form name="advancedquery" action="search.php" method="get">
<table class="searchform" border="0">
<tr><th>Enter word or phrase:</th><td><input type="text" size="40" name="keyword"></td></tr>
<tr><th>Type of search:</th><td><input type="radio" name="mode" value="phonetic">Phonetic
<input type="radio" name="mode" value="exact">Exact Phrase
<input type="radio" name="mode" value="synonym">Synonym</td>
</tr>
<tr><td></td><td><input type="submit" value="Submit"><input type="reset" value="Reset"></td></tr> 
</table>
</form>';


print "</div>";

include("footer.html");
?>

</body>
</html>