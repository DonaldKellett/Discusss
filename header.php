<?php
/*
  header.php
  Discusss
  (c) Donald Leung.  All rights reserved.
  Released under the Creative Commons Attribution 3.0 License
*/

# Placeholder for Search Form - Feel Free to Edit
$search_form_placeholder = "Search Discusss ... ";

# Actual Header Content (Do NOT alter unless you have used this template before)
echo '<header id="header">
            <h1><a href="index.php">Discusss</a></h1>
            <nav class="main">
              <ul>
                <li class="search">
                  <a class="fa-search" href="#search">Search</a>
                  <form id="search" method="get" action="search.php">
                    <input type="text" name="q" placeholder="' . $search_form_placeholder . '" />
                  </form>
                </li>
              </ul>
            </nav>
          </header>';
?>
