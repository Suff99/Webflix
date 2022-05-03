package me.craig.release;

public enum ReleaseType {
    TV("tv", "series"), MOVIE("movie", "movie");

    private final String internal, api;

    ReleaseType(String api, String internal) {
        this.api = api;
        this.internal = internal;
    }

    public String getApi() {
        return api;
    }

    public String getInternal() {
        return internal;
    }
}
