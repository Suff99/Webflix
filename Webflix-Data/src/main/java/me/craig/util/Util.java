package me.craig.util;

import com.google.gson.JsonArray;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonPrimitive;
import me.craig.Generate;
import me.craig.release.Release;
import me.craig.release.ReleaseType;

import java.io.IOException;
import java.net.URL;
import java.nio.ByteBuffer;
import java.nio.charset.StandardCharsets;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;

public class Util {

    public static Integer[] getCategoryIds(JsonArray jsonObject) {
        ArrayList<Integer> ids = new ArrayList<>();
        for (JsonElement jsonElement : jsonObject) {
            String categoryName = jsonElement.getAsJsonObject().get("name").getAsString();
            int id = getInternalIdForCategory(categoryName);
            ids.add(id);
        }
        return ids.toArray(new Integer[0]);
    }

    private static int getInternalIdForCategory(String categoryName) {
        String query = "SELECT * FROM `wf_categories` WHERE `name`= '" + categoryName + "';";
        ResultSet results = Generate.SQL.executeQuery(query);
        try {
            results.next();
            return results.getInt(1);
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }
    }

    public static String[] getAllLanguages(JsonElement lang) {
        ArrayList<String> languages = new ArrayList<>();
        for (JsonElement element : lang.getAsJsonArray()) {
            JsonPrimitive name = element.getAsJsonObject().get("english_name").getAsJsonPrimitive();
            if (!languages.contains(name.getAsString())) {
                languages.add(name.getAsString());
            }
        }
        return languages.toArray(new String[0]);
    }

    public static void addReleaseToDb(Release release) {
        String query = "INSERT INTO wf_releases(`title`, `tagline`, `description`, `trailer`,`watch_link`, `date`, `images`, `release_type`, `categories`, `additional_info`) VALUES ('" + ensureUtf8(release.getTitle()) + "','" + ensureUtf8(release.getTagline()) + "','" + ensureUtf8(release.getDescription()) + "','" + release.getVideo() + "','" + ensureUtf8(release.getHomepageUrl()) + "','" + release.getDate() + "','" + release.imageJson() + "','" + release.getReleaseType().getInternal() + "','" + Generate.GSON.toJson(release.getCategoryIds()) + "','" + release.moreInfo() + "')";
        Generate.SQL.executeUpdate(query);
    }

    public static String ensureUtf8(String s){
        ByteBuffer buffer = StandardCharsets.UTF_8.encode(s.replace("'","''"));
        return StandardCharsets.UTF_8.decode(buffer).toString();
    }

    public static String getVideoForRelease(ReleaseType releaseType, int id) {
        URL requestUrl;
        try {
            requestUrl = new URL("https://api.themoviedb.org/3/" + releaseType.getApi() + "/" + id + "/videos?api_key=" + Generate.API_KEY);
            JsonObject jsonObject = Generate.getApiResponse(requestUrl);
            for (JsonElement results : jsonObject.getAsJsonArray("results")) {
                JsonObject video = results.getAsJsonObject();
                if (video.get("site").getAsString().equalsIgnoreCase("youtube")) {
                    return video.get("key").getAsJsonPrimitive().getAsString();
                }
            }
        } catch (IOException e) {
            //   throw new RuntimeException(e);
        }
        return "n5Q4Y5nLvrg";
    }

}
