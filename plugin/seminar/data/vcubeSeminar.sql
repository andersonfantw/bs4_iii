DROP TABLE bookshelf2_vcube_seminar_calendar;
CREATE TABLE bookshelf2_vcube_seminar_calendar (
  vsc_seminarkey VARCHAR(255) NOT NULL,
  u_id INTEGER NOT NULL,
  vsc_roomkey VARCHAR(255) NOT NULL,
  vsc_name VARCHAR(255) NOT NULL,
  vsc_start TIMESTAMP NULL,
  vsc_end TIMESTAMP NULL,
  vsc_url VARCHAR(255) NULL,
  vsc_max INTEGER NULL,
  createdate TIMESTAMP NULL,
  PRIMARY KEY (vmc_seminarkey)
);

DROP TABLE bookshelf2_vcube_seminar_calendar_group;
CREATE TABLE bookshelf2_vcube_seminar_calendar_group (
  vsc_seminarkey VARCHAR(255) NOT NULL,
  g_id INTEGER NOT NULL,
  PRIMARY KEY (vsc_seminarkey,g_id)
);

DROP TABLE bookshelf2_vcube_seminar_calendar_user;
CREATE TABLE bookshelf2_vcube_seminar_calendar_user (
  vsc_seminarkey VARCHAR(255) NOT NULL,
  bu_id INTEGER NOT NULL,
  vscu_participant VARCHAR(255) NOT NULL,
  vscu_invitationkey VARCHAR(255) NOT NULL,
  vscu_url VARCHAR(255) NOT NULL,
  PRIMARY KEY (vsc_seminarkey,bu_id)
);