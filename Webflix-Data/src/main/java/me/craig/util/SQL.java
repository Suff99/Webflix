package me.craig.util;

import java.sql.*;

public class SQL {

    private Connection connection;

    public void resetDatabase() {
        String[] commands = new String[]{"SET FOREIGN_KEY_CHECKS = 0;", "TRUNCATE wf_releases;", "TRUNCATE wf_comments;", "SET FOREIGN_KEY_CHECKS = 1;"};
        for (String command : commands) {
            executeUpdate(command);
        }
    }

    public void connect(String connectionUrl, String userName, String password) {
        try {
            connection = DriverManager.getConnection(connectionUrl, userName, password);
        } catch (SQLException e) {
            //     throw new RuntimeException(e);
        }
    }

    public void executeUpdate(String query) {
        try {
            PreparedStatement ps = connection.prepareStatement(query);
            int rs = ps.executeUpdate();
        } catch (SQLException e) {
            //     throw new RuntimeException(e);
        }
    }

    public ResultSet executeQuery(String query) {
        ResultSet rs = null;
        try {
            PreparedStatement ps = connection.prepareStatement(query);
            rs = ps.executeQuery();
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }
        return rs;
    }


}
