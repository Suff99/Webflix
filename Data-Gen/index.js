import fetch from "node-fetch";
import mysql from "mysql";
import fs from "fs";

let databaseInfo = JSON.parse(fs.readFileSync('database.json'));
const API_KEY = databaseInfo.api_key;
const settings = { method: "Get" };
const TV_SHOWS = "tv";
const MOVIE = "movie";

//
var con = mysql.createConnection({
  host: databaseInfo.host,
  user: databaseInfo.user,
  password: databaseInfo.password,
  database: databaseInfo.database
});

con.connect(function (err) {
  if (err) throw err;
  console.log("Connected!");
});


await getReleases(30, TV_SHOWS)
await getReleases(30, MOVIE)

function getReleases(pages, type) {
  for (let index = 1; index < pages; index++) {
    var pageURL = 'https://api.themoviedb.org/3/'+type+'/popular?api_key=' + API_KEY + '&page=' + index;
    fetch(pageURL, settings).then(res => res.json()).then((json) => {
      json.results.forEach(element => {
        handleRelease(element, type);
      });
    });
  }
}

async function handleRelease(release, type) {


  // Obtain more information on the release 
  var releaseInfo = "https://api.themoviedb.org/3/"+type+"/" + release.id + "?api_key=" + API_KEY;
  console.log(releaseInfo)

  var data = await fetch(releaseInfo);
  var json = await data.json();

  // Obtain backdrop and poster of the release and store to Json
  var images = {
    'poster': 'https://image.tmdb.org/t/p/original' + release.poster_path,
    'backdrop': 'https://image.tmdb.org/t/p/original' + release.backdrop_path,
  }


  // Organize languages into a array from the given data.
  // The MovieDB Api provides each language the movie is availible in, but there are multiple that are considered 'English', this causes duplicates.
  // To avoid this, we must cycle through the languages and check if the language name already exists within the array
  var languages = []

  json.spoken_languages.forEach(element => {
    if (!languages.includes(element)) {
      languages.push(element.english_name)
    }
  });


  //TODO Do int IDs
  var categories = [];

  json.genres.forEach(element => {
    if (!categories.includes(element)) {
      categories.push(element.name)
    }
  });



  console.log(categories);

  // Store availible languages and the run time to file 
  var additional_info = {
    'runtime': type == MOVIE ? (json.runtime + " Minutes") : (json.number_of_seasons + " Seasons"),
    'languages': languages
  }

  addToDatabase(type == MOVIE ? release.release_date : release.first_air_date, type == MOVIE ? release.title : release.name, type == MOVIE ? json.tagline : "", release.overview, 'TEygSwHWhfA', images, type == MOVIE ? MOVIE : 'series', json.homepage, categories, additional_info)
}


function addToDatabase(date, release_title, tagline, desc, trailer_id, image_json, release_type, watch_link, categories, additional_info) {
  var addReleaseQuery = "INSERT INTO wf_releases(`title`, `tagline`, `description`, `trailer`,`watch_link`, `date`, `images`, `release_type`, `categories`, `additional_info`) VALUES ('" + release_title + "','" + tagline + "','" + desc + "','" + trailer_id + "','" + watch_link + "','" + date + "','" + JSON.stringify(image_json) + "','" + release_type + "','" + JSON.stringify(categories) + "','" + JSON.stringify(additional_info) + "')";

  con.query(addReleaseQuery, function (err, result) {
    if (err) {
      console.log("Could not add " + release_title)
    } else {
      console.log("Added " + release_title)
    }
  });
}
