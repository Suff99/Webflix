package me.craig.release;

import com.google.gson.JsonArray;
import com.google.gson.JsonObject;
import com.google.gson.JsonPrimitive;
import me.craig.util.Util;

public class Release {

    private final String date;
    private ReleaseType releaseType = null;
    private String title, tagline = "", description = "";
    private String backdropUrl, posterUrl = "", homepageUrl = "";
    private Integer[] categoryIds = new Integer[]{};
    private String[] languages = new String[]{};
    private String video;

    private String length;

    public Release(ReleaseType releaseType, JsonObject jsonElement) {
        this.releaseType = releaseType;
        boolean isTv = releaseType == ReleaseType.TV;

        this.title = jsonElement.get(isTv ? "name" : "title").getAsString();
        this.tagline = jsonElement.get("tagline").getAsString();
        this.description = jsonElement.get("overview").getAsString();
        this.backdropUrl = jsonElement.get("backdrop_path").isJsonNull() ? "" : jsonElement.get("backdrop_path").getAsString();
        this.posterUrl = jsonElement.get("poster_path").isJsonNull() ? "" : jsonElement.get("poster_path").getAsString();
        this.homepageUrl = jsonElement.get("homepage").getAsString();
        if(jsonElement.get(isTv ? "first_air_date" : "release_date").isJsonNull()){
            this.date = "Unreleased!";
        } else {
            this.date = jsonElement.get(isTv ? "first_air_date" : "release_date").getAsJsonPrimitive().getAsString();
        }
        this.categoryIds = Util.getCategoryIds(jsonElement.getAsJsonArray("genres"));
        this.languages = Util.getAllLanguages(jsonElement.get("spoken_languages"));
        this.length = jsonElement.get(isTv ? "number_of_seasons" : "runtime").getAsInt() + " " + (isTv ? "Seasons" : "Minutes");
    }

    public String getLength() {
        return length;
    }

    public void setLength(String length) {
        this.length = length;
    }

    public String getHomepageUrl() {
        return homepageUrl;
    }

    public void setHomepageUrl(String homepageUrl) {
        this.homepageUrl = homepageUrl;
    }

    public String getDate() {
        return date;
    }

    public ReleaseType getReleaseType() {
        return releaseType;
    }

    public void setReleaseType(ReleaseType releaseType) {
        this.releaseType = releaseType;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getTagline() {
        return tagline;
    }

    public void setTagline(String tagline) {
        this.tagline = tagline;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public String getBackdropUrl() {
        return backdropUrl;
    }

    public void setBackdropUrl(String backdropUrl) {
        this.backdropUrl = backdropUrl;
    }

    public String getPosterUrl() {
        return posterUrl;
    }

    public void setPosterUrl(String posterUrl) {
        this.posterUrl = posterUrl;
    }

    public Integer[] getCategoryIds() {
        return categoryIds;
    }

    public void setCategoryIds(Integer[] categoryIds) {
        this.categoryIds = categoryIds;
    }

    public String[] getLanguages() {
        return languages;
    }

    public void setLanguages(String[] languages) {
        this.languages = languages;
    }

    public JsonObject imageJson() {
        JsonObject jsonObject = new JsonObject();
        jsonObject.add("poster", new JsonPrimitive("https://image.tmdb.org/t/p/original" + getPosterUrl()));
        jsonObject.add("backdrop", new JsonPrimitive("https://image.tmdb.org/t/p/original" + getBackdropUrl()));
        return jsonObject;
    }

    public JsonObject moreInfo() {
        JsonObject jsonObject = new JsonObject();

        JsonArray langArray = new JsonArray();

        for (String language : getLanguages()) {
            langArray.add(language);
        }

        jsonObject.add("languages", langArray);
        jsonObject.add("runtime", new JsonPrimitive(getLength()));
        return jsonObject;
    }

    public void setVideoString(String key) {
        this.video = key;
    }

    public String getVideo() {
        return video;
    }
}
