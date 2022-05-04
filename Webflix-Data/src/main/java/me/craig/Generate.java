package me.craig;


import com.google.gson.*;
import me.craig.release.Release;
import me.craig.release.ReleaseType;
import me.craig.util.SQL;
import me.craig.util.Util;

import javax.net.ssl.HttpsURLConnection;
import java.io.*;
import java.net.URL;
import java.util.ArrayList;

import static me.craig.util.Util.addReleaseToDb;

public class Generate {

    private static final String USER_AGENT = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.41 Safari/537.36";
    public static Gson GSON = new GsonBuilder().disableHtmlEscaping().setPrettyPrinting().create();
    public static String API_KEY = "";
    public static Config CONFIG = new Config();
    public static SQL SQL = new SQL();

    public static void main(String[] args) throws IOException {

        CONFIG.writeConfigIfNotPresent();
        CONFIG.readConfig();

        SQL.connect(CONFIG.getDatabaseUrl(), CONFIG.getDatabaseUser(), CONFIG.getDatabasePassword());

        API_KEY = CONFIG.getApiKey();

        SQL.resetDatabase();

        for (ReleaseType value : ReleaseType.values()) {
            getReleases(30, value);
        }

        System.out.println("Done!");
        System.exit(0);
    }



    public static ArrayList<Release> getReleases(int pages, ReleaseType releaseType) throws IOException {
        ArrayList<Release> releases = new ArrayList<>();
        for (int i = 1; i < pages; i++) {
            String pageURL = "https://api.themoviedb.org/3/" + releaseType.getApi() + "/popular?api_key=" + API_KEY + "&page=" + i;
            JsonArray response = getApiResponse(new URL(pageURL)).getAsJsonArray("results");
            for (JsonElement jsonElement : response) {
                JsonObject releaseData = getFullDetails(releaseType, jsonElement.getAsJsonObject().get("id").getAsInt());
                String videoString = Util.getVideoForRelease(releaseType, jsonElement.getAsJsonObject().get("id").getAsInt());
                Release release = new Release(releaseType, releaseData);
                release.setVideoString(videoString);
                releases.add(release);
                if(!releaseData.get("adult").getAsBoolean()) {
                    addReleaseToDb(release);
                    System.out.println("Created: " + release.getTitle() + "\n" + release);
                } else {
                    System.out.println("Did not add " + release.getTitle() + ", as it is a adult movie!");
                }
            }
        }
        return releases;
    }

    private static JsonObject getFullDetails(ReleaseType releaseType, int id) {
        String releaseInfo = "https://api.themoviedb.org/3/" + releaseType.getApi() + "/" + id + "?api_key=" + API_KEY;
        System.out.println(releaseInfo);
        JsonObject result = null;
        try {
            result = getApiResponse(new URL(releaseInfo));
        } catch (IOException e) {
            throw new RuntimeException(e);
        }
        return result;
    }


    public static JsonObject getApiResponse(URL url) throws IOException {
        HttpsURLConnection uc = (HttpsURLConnection) url.openConnection();
        uc.connect();
        uc = (HttpsURLConnection) url.openConnection();
        uc.addRequestProperty("User-Agent", USER_AGENT);
        InputStream inputStream = uc.getInputStream();
        BufferedReader br = new BufferedReader(new InputStreamReader(inputStream));
        return parse(br);
    }

    public static JsonObject parse(Reader reader) {
        return GSON.fromJson(reader, JsonObject.class);
    }


}
