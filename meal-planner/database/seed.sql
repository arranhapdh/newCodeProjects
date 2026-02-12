-- Default admin user (password: admin123)
INSERT OR IGNORE INTO users (username, email, password_hash, display_name, role)
VALUES ('admin', 'admin@mealplanner.local', '$2y$10$YfGzTEbMQg0tCmBwvSBKzeYFVLDkiYV2sOah31bXkqFMnJBkOG1mu', 'Admin', 'admin');
