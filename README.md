## CURL
There is a main page where you can login, log up, watch weather forecast and get to the anonymous stealer page.
1. When you login you become user and get to user-private home page, where you can request for list of users who lives in any city.
2. When you log up you indicate your city, getting user status and doctrine ORM determine:
    1. That you live in the city
    2. That this city has you like a citizen
3. You can watch weather forecast regarding real meteo-resource being anonymous searching for "Минск", "Брест", or patterns like "Гомел" or "витеб".
4. When you get to stealer page you dont'n need to authorize so you are still anonymous but ypu can get user-private information with a help of so-called illegal search:
    1. CURL bot generates random username and password and try to log up like a human would do.
    2. CURL bot try log in according it's new temp username and password.
    3. CURL bot sends needed post ti get user-private list of data via legal search panel.
    
Need to notice that Symfony 4 automatically generates so-called SCRF-token when you create form from prepared form-object which is known by inner system and which my CURL bot need to get and cut off in advance to be be able to prove form submission.