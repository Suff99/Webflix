package me.craig;

import com.google.gson.JsonObject;

import java.io.*;

public class Config {

    private String API_KEY, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_URL = "";

    public String getApiKey() {
        return API_KEY;
    }

    public String getDatabaseUser() {
        return DATABASE_USERNAME;
    }

    public String getDatabasePassword() {
        return DATABASE_PASSWORD;
    }

    public String getDatabaseUrl() {
        return DATABASE_URL;
    }

    public void writeConfigIfNotPresent() {
        File file = new File("./config.json");
        try {
            if (file.exists()) return;
            FileWriter writer = new FileWriter(file);
            Generate.GSON.toJson(this, writer);
            writer.close();
        } catch (IOException e) {
            throw new RuntimeException(e);
        }
    }

    public void readConfig() {
        File file = new File("./config.json");
        JsonObject json = null;
        try {
            json = Generate.parse(new FileReader(file));
            this.API_KEY = json.get("API_KEY").getAsString();
            this.DATABASE_USERNAME = json.get("DATABASE_USERNAME").getAsString();
            this.DATABASE_PASSWORD = json.get("DATABASE_PASSWORD").getAsString();
            this.DATABASE_URL = json.get("DATABASE_URL").getAsString();
        } catch (FileNotFoundException e) {
            throw new RuntimeException(e);
        }
    }


}
