CREATE TABLE member
(
member_id INT NOT NULL AUTO_INCREMENT,
member_name VARCHAR(64) NOT NULL,
member_passwd VARCHAR(255) NOT NULL,
member_mail VARCHAR(64) NOT NULL,
member_fullname VARCHAR(64),
member_gender CHAR(1),
member_status CHAR(1) NOT NULL DEFAULT 'u',
member_date DATETIME NOT NULL DEFAULT NOW(),

/* social accounts */
member_facebook VARCHAR(64),
member_twitter VARCHAR(64),
member_instagram VARCHAR(64),

PRIMARY KEY (member_id)
); 

CREATE TABLE category
(
cat_id INT NOT NULL AUTO_INCREMENT,
cat_name VARCHAR(64) NOT NULL,
cat_description VARCHAR(64) NOT NULL,

PRIMARY KEY (cat_id)
); 

CREATE TABLE topic
(
topic_id INT NOT NULL AUTO_INCREMENT,
cat_id INT NOT NULL,
topic_name VARCHAR(128) NOT NULL,
topic_down_vote INT  NOT NULL DEFAULT 0,
topic_up_vote INT  NOT NULL DEFAULT 0,
topic_date DATETIME NOT NULL DEFAULT NOW(),

PRIMARY KEY (topic_id),
FOREIGN KEY (cat_id) REFERENCES category(cat_id) ON DELETE CASCADE
); 

CREATE TABLE entry
(
entry_id INT NOT NULL AUTO_INCREMENT,
member_id INT NOT NULL,
entry_content TEXT NOT NULL,
entry_down_vote INT NOT NULL DEFAULT 0,
entry_up_vote INT NOT NULL DEFAULT 0,
entry_date DATETIME NOT NULL DEFAULT NOW(),

PRIMARY KEY (entry_id)
);

CREATE TABLE entry_topic
(
entry_id INT NOT NULL,
topic_id INT NOT NULL,

FOREIGN KEY (entry_id) REFERENCES entry(entry_id) ON DELETE CASCADE,
FOREIGN KEY (topic_id) REFERENCES topic(topic_id) ON DELETE CASCADE
);

CREATE TABLE entry_reply
(
entry_id INT NOT NULL,
reply_id INT NOT NULL,

FOREIGN KEY (entry_id) REFERENCES entry(entry_id) ON DELETE CASCADE,
FOREIGN KEY (reply_id) REFERENCES entry(entry_id) ON DELETE CASCADE
);

CREATE TABLE member_subscribe
(
member_id INT NOT NULL,
cat_id INT NOT NULL,

FOREIGN KEY (member_id) REFERENCES member(member_id) ON DELETE CASCADE,
FOREIGN KEY (cat_id) REFERENCES category(cat_id) ON DELETE CASCADE
); 

CREATE TABLE member_topic
(
member_id INT NOT NULL,
topic_id INT NOT NULL,
vote CHAR(1) NOT NULL,

PRIMARY KEY (member_id, topic_id),
FOREIGN KEY (member_id) REFERENCES member(member_id),
FOREIGN KEY (topic_id) REFERENCES topic(topic_id) ON DELETE CASCADE
); 

CREATE TABLE member_entry
(
member_id INT NOT NULL,
entry_id INT NOT NULL,
vote CHAR(1) NOT NULL,

FOREIGN KEY (member_id) REFERENCES member(member_id),
FOREIGN KEY (entry_id) REFERENCES entry(entry_id) ON DELETE CASCADE
); 

CREATE TABLE member_message
(
member_id_sender INT NOT NULL,
member_id_receiver INT NOT NULL,
message_date DATETIME NOT NULL DEFAULT NOW(),
message_content TEXT NOT NULL,
message_read CHAR(1) NOT NULL DEFAULT 'f',

FOREIGN KEY (member_id_sender) REFERENCES member(member_id),
FOREIGN KEY (member_id_receiver) REFERENCES member(member_id)
);
