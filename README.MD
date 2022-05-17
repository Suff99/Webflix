![workflow](https://github.com/Suff99/webflix/actions/workflows/main.yml/badge.svg)

# Webflix

A company in Edinburgh want to launch their new online streaming service called 'Webflix' that offers a wide variety of
TV programmes, films, and documentaries. Users can subscribe to the system as either a 'Basic' subscription which is
free or 'Premium' subscription for a fee (£99.99/year). Further details relating to the proposed system can be obtained
from the client (i.e. your lecturer), who will be available for a discussion in a weeks’ time.

# Required Pages

- [x] Login
- [x] Register
- [ ] Forgot Password
- [x] Payment
- [x] Home
- [x] Movies
- [x] TV Shows
- [x] Single Movie
- [x] Single TV Show

# Testing
In order to test the application against the test demo site at https://craig.software/webflix/
You will need to:
- Ensure that you have set up node correctly before this, to do this install node from https://nodejs.org/en/download/ 
- Open a command window pointing at the root of this project
- Run ``cd tests``
- Validate you are in ./tests
- run ``npx cypress run`` or ``npx cypress open`` 
- Happy testing 