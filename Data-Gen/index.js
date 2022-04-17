import fetch from "node-fetch";
import mysql from "mysql";
import fs from "fs";

let databaseInfo = JSON.parse(fs.readFileSync('database.json'));
const API_KEY = databaseInfo.api_key;
const settings = { method: "Get" };

//
var con = mysql.createConnection({
  host: databaseInfo.host,
  user: databaseInfo.user,
  password: databaseInfo.password,
  database : databaseInfo.database
});

con.connect(function(err) {
  if (err) throw err;
  console.log("Connected!");
});


getMovies(30)

function getMovies(pages) {
  for (let index = 1; index < pages; index++) {
    var pageURL = 'https://api.themoviedb.org/3/movie/popular?api_key=' + API_KEY + '&page=' + index;
    fetch(pageURL, settings).then(res => res.json()).then((json) => {
      console.log(json)
      json.results.forEach(element => {
          handleMovie(element);
        });
    });
  }
}

function handleMovie(movie) {

  var releaseInfo = "https://api.themoviedb.org/3/movie/" + movie.id + "?api_key=" + API_KEY;

  var images = {
    'poster' : 'https://image.tmdb.org/t/p/original' + movie.poster_path,
    'backdrop' : 'https://image.tmdb.org/t/p/original' + movie.backdrop_path,
  }

  var additional_info = {
    'runtime' : 1,
    'languages' : ['ENGLAND' , 'SCOTLAND']
  }

  var categories = ['1' , '2' ]

  addToDatabase(movie.release_date, movie.title, movie.overview, movie.overview, 'TEygSwHWhfA', images, 'movie', releaseInfo, categories, additional_info)
}


function addToDatabase(date, release_title, tagline, desc, trailer_id, image_json, release_type, watch_link, categories, additional_info) {
  var addReleaseQuery = "INSERT INTO wf_releases(`title`, `tagline`, `description`, `trailer`,`watch_link`, `date`, `images`, `release_type`, `categories`, `additional_info`) VALUES ('"+release_title+"','"+tagline+"','"+desc+"','"+trailer_id+"','"+watch_link+"','"+date+"','"+JSON.stringify(image_json)+"','"+release_type+"','"+JSON.stringify(categories)+"','"+JSON.stringify(additional_info)+"')";
  
  con.query(addReleaseQuery, function (err, result) {
    if (err) {
      console.log("Could not add " + release_title)
    } else {
      console.log("Added " + release_title)
    }
  });
}
