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

print '
<h2>Phonetic Search</h2>
<form name="phoneticquery" action="search.php" method="get">
<table class="searchform" border="0">
<tr><th>Phonetic Search:</th><td><input type="text" size="40" name="keyword"></td></tr>
<input type="hidden" name="mode" value="phonetic">
<tr><td></td><td><input type="submit" value="Submit"><input type="reset" value="Reset"></td></tr> 
</table>
</form>';

print '
<h2>Exact Phrase</h2>
<form name="exactquery" action="search.php" method="get">
<table class="searchform" border="0">
<tr><th>Enter phrase:</th><td><input type="text" size="40" name="keyword"></td></tr>
<input type="hidden" name="mode" value="exact">
<tr><td></td><td><input type="submit" value="Submit"><input type="reset" value="Reset"></td></tr> 
</table>
</form>';

print "</div>";

include("footer.html");
?>

</body>
</html>