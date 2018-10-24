# agora
Recent popular topics according to Wiki.api

Can be viewed here: https://collaber.org/agora/

Designed specifically to be viewed on mobile. 

The idea behind this little project was to make a minimal popular topics 
service that aggregates information and news based on what people have 
recently been searching on wikipedia. It pulls in the wiki intro for said
topic, uses the google search engine api to find a relevant image for the
topic, uses the newsapi to find relevant popular news articles on the topic, 
and lastly provides media links to quickly search popular search engines
and social media networks for the topic.

This was mostly just a utility service I wanted for myself. Instead of needing
to do these searches myself, I just wanted a single place where all of them
are gathered and only a click away. Did not design the css to be robust across
platforms and devices. It was built for me so I could check what was going on
in the world quickly, so its modeled to work best with galaxy 9s. That being
said, it shouldn't be horrendous on other devices since fairly common css
practices were used. 

---------------------------------------------------------------------------------------------------------------

The code in this repository assumes a mysql DB has been set up with the
relevant tables and columns. It also assumes a cron job that runs 
“dailyArticlesUpdate.php” once a day has been set up as well. 
