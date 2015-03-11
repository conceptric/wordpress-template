CREATE DATABASE wp_template; 
GRANT USAGE ON *.* TO webdeveloper@localhost IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON wp_template.* TO webdeveloper@localhost;
FLUSH PRIVILEGES;