--
-- PostgreSQL database dump
--

-- Dumped from database version 9.2.0
-- Dumped by pg_dump version 9.2.0
-- Started on 2013-02-14 21:06:33

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 185 (class 1259 OID 92723)
-- Name: action_comments; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE action_comments (
    id integer NOT NULL,
    action_id integer NOT NULL,
    user_id integer NOT NULL,
    comment text NOT NULL,
    created timestamp with time zone DEFAULT now() NOT NULL
);


--
-- TOC entry 184 (class 1259 OID 92721)
-- Name: action_comments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE action_comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2095 (class 0 OID 0)
-- Dependencies: 184
-- Name: action_comments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE action_comments_id_seq OWNED BY action_comments.id;


--
-- TOC entry 2096 (class 0 OID 0)
-- Dependencies: 184
-- Name: action_comments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('action_comments_id_seq', 4, true);


--
-- TOC entry 179 (class 1259 OID 92655)
-- Name: action_types; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE action_types (
    id integer NOT NULL,
    name character varying(50),
    require_deadline boolean DEFAULT false NOT NULL,
    active boolean DEFAULT true NOT NULL
);


--
-- TOC entry 178 (class 1259 OID 92653)
-- Name: action_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE action_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2097 (class 0 OID 0)
-- Dependencies: 178
-- Name: action_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE action_types_id_seq OWNED BY action_types.id;


--
-- TOC entry 2098 (class 0 OID 0)
-- Dependencies: 178
-- Name: action_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('action_types_id_seq', 5, true);


--
-- TOC entry 183 (class 1259 OID 92691)
-- Name: actions; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE actions (
    id integer NOT NULL,
    user_id integer NOT NULL,
    course_membership_id integer NOT NULL,
    action_type_id integer NOT NULL,
    description text,
    created timestamp with time zone DEFAULT now() NOT NULL,
    modified timestamp with time zone,
    deadline timestamp with time zone,
    handled_id integer,
    handled_time timestamp with time zone
);


--
-- TOC entry 191 (class 1259 OID 92781)
-- Name: actions_exercises; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE actions_exercises (
    id integer NOT NULL,
    action_id integer NOT NULL,
    exercise_id integer NOT NULL
);


--
-- TOC entry 190 (class 1259 OID 92779)
-- Name: actions_exercises_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE actions_exercises_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2099 (class 0 OID 0)
-- Dependencies: 190
-- Name: actions_exercises_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE actions_exercises_id_seq OWNED BY actions_exercises.id;


--
-- TOC entry 2100 (class 0 OID 0)
-- Dependencies: 190
-- Name: actions_exercises_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('actions_exercises_id_seq', 7, true);


--
-- TOC entry 182 (class 1259 OID 92689)
-- Name: actions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE actions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2101 (class 0 OID 0)
-- Dependencies: 182
-- Name: actions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE actions_id_seq OWNED BY actions.id;


--
-- TOC entry 2102 (class 0 OID 0)
-- Dependencies: 182
-- Name: actions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('actions_id_seq', 5, true);


--
-- TOC entry 195 (class 1259 OID 92820)
-- Name: chat_messages; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE chat_messages (
    id integer NOT NULL,
    user_id integer,
    content text,
    created timestamp with time zone
);


--
-- TOC entry 194 (class 1259 OID 92818)
-- Name: chat_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE chat_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2103 (class 0 OID 0)
-- Dependencies: 194
-- Name: chat_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE chat_messages_id_seq OWNED BY chat_messages.id;


--
-- TOC entry 2104 (class 0 OID 0)
-- Dependencies: 194
-- Name: chat_messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('chat_messages_id_seq', 1, false);


--
-- TOC entry 181 (class 1259 OID 92665)
-- Name: course_memberships; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE course_memberships (
    id integer NOT NULL,
    course_id integer NOT NULL,
    student_id integer NOT NULL,
    quit_id integer,
    quit_time timestamp with time zone,
    comment text
);


--
-- TOC entry 180 (class 1259 OID 92663)
-- Name: course_memberships_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE course_memberships_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2105 (class 0 OID 0)
-- Dependencies: 180
-- Name: course_memberships_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE course_memberships_id_seq OWNED BY course_memberships.id;


--
-- TOC entry 2106 (class 0 OID 0)
-- Dependencies: 180
-- Name: course_memberships_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('course_memberships_id_seq', 5, true);


--
-- TOC entry 169 (class 1259 OID 92589)
-- Name: courses; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE courses (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    starttime timestamp with time zone NOT NULL,
    endtime timestamp with time zone NOT NULL
);


--
-- TOC entry 168 (class 1259 OID 92587)
-- Name: courses_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE courses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2107 (class 0 OID 0)
-- Dependencies: 168
-- Name: courses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE courses_id_seq OWNED BY courses.id;


--
-- TOC entry 2108 (class 0 OID 0)
-- Dependencies: 168
-- Name: courses_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('courses_id_seq', 2, true);


--
-- TOC entry 189 (class 1259 OID 92763)
-- Name: courses_users; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE courses_users (
    id integer NOT NULL,
    course_id integer NOT NULL,
    user_id integer NOT NULL
);


--
-- TOC entry 188 (class 1259 OID 92761)
-- Name: courses_users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE courses_users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2109 (class 0 OID 0)
-- Dependencies: 188
-- Name: courses_users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE courses_users_id_seq OWNED BY courses_users.id;


--
-- TOC entry 2110 (class 0 OID 0)
-- Dependencies: 188
-- Name: courses_users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('courses_users_id_seq', 8, true);


--
-- TOC entry 193 (class 1259 OID 92799)
-- Name: email_messages; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE email_messages (
    id integer NOT NULL,
    sender character varying(255),
    receiver character varying(255),
    subject character varying(255),
    content text,
    course_membership_id integer,
    action_id integer,
    sent_time timestamp with time zone,
    read_time timestamp with time zone
);


--
-- TOC entry 192 (class 1259 OID 92797)
-- Name: email_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE email_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2111 (class 0 OID 0)
-- Dependencies: 192
-- Name: email_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE email_messages_id_seq OWNED BY email_messages.id;


--
-- TOC entry 2112 (class 0 OID 0)
-- Dependencies: 192
-- Name: email_messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('email_messages_id_seq', 1, false);


--
-- TOC entry 177 (class 1259 OID 92642)
-- Name: exercises; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE exercises (
    id integer NOT NULL,
    course_id integer NOT NULL,
    exercise_number integer NOT NULL,
    exercise_name character varying(255),
    starttime timestamp with time zone,
    endtime timestamp with time zone,
    review_starttime timestamp with time zone,
    review_endtime timestamp with time zone
);


--
-- TOC entry 176 (class 1259 OID 92640)
-- Name: exercises_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE exercises_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2113 (class 0 OID 0)
-- Dependencies: 176
-- Name: exercises_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE exercises_id_seq OWNED BY exercises.id;


--
-- TOC entry 2114 (class 0 OID 0)
-- Dependencies: 176
-- Name: exercises_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('exercises_id_seq', 4, true);


--
-- TOC entry 175 (class 1259 OID 92624)
-- Name: groups; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE groups (
    id integer NOT NULL,
    course_id integer NOT NULL,
    user_id integer NOT NULL
);


--
-- TOC entry 174 (class 1259 OID 92622)
-- Name: groups_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2115 (class 0 OID 0)
-- Dependencies: 174
-- Name: groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE groups_id_seq OWNED BY groups.id;


--
-- TOC entry 2116 (class 0 OID 0)
-- Dependencies: 174
-- Name: groups_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('groups_id_seq', 3, true);


--
-- TOC entry 187 (class 1259 OID 92745)
-- Name: groups_students; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE groups_students (
    id integer NOT NULL,
    group_id integer NOT NULL,
    student_id integer NOT NULL
);


--
-- TOC entry 186 (class 1259 OID 92743)
-- Name: groups_students_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE groups_students_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2117 (class 0 OID 0)
-- Dependencies: 186
-- Name: groups_students_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE groups_students_id_seq OWNED BY groups_students.id;


--
-- TOC entry 2118 (class 0 OID 0)
-- Dependencies: 186
-- Name: groups_students_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('groups_students_id_seq', 5, true);


--
-- TOC entry 171 (class 1259 OID 92597)
-- Name: students; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE students (
    id integer NOT NULL,
    student_number integer NOT NULL,
    last_name character varying(255) NOT NULL,
    first_name character varying(255) NOT NULL,
    email character varying(255) NOT NULL
);


--
-- TOC entry 170 (class 1259 OID 92595)
-- Name: students_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE students_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2119 (class 0 OID 0)
-- Dependencies: 170
-- Name: students_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE students_id_seq OWNED BY students.id;


--
-- TOC entry 2120 (class 0 OID 0)
-- Dependencies: 170
-- Name: students_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('students_id_seq', 5, true);


--
-- TOC entry 173 (class 1259 OID 92610)
-- Name: users; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE users (
    id integer NOT NULL,
    basic_user_account character varying(30),
    last_name character varying(255) NOT NULL,
    first_name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(50) NOT NULL,
    is_admin boolean DEFAULT false NOT NULL,
    last_login timestamp with time zone
);


--
-- TOC entry 172 (class 1259 OID 92608)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2121 (class 0 OID 0)
-- Dependencies: 172
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- TOC entry 2122 (class 0 OID 0)
-- Dependencies: 172
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('users_id_seq', 4, true);


--
-- TOC entry 2013 (class 2604 OID 92726)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY action_comments ALTER COLUMN id SET DEFAULT nextval('action_comments_id_seq'::regclass);


--
-- TOC entry 2007 (class 2604 OID 92658)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY action_types ALTER COLUMN id SET DEFAULT nextval('action_types_id_seq'::regclass);


--
-- TOC entry 2011 (class 2604 OID 92694)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions ALTER COLUMN id SET DEFAULT nextval('actions_id_seq'::regclass);


--
-- TOC entry 2017 (class 2604 OID 92784)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions_exercises ALTER COLUMN id SET DEFAULT nextval('actions_exercises_id_seq'::regclass);


--
-- TOC entry 2019 (class 2604 OID 92823)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY chat_messages ALTER COLUMN id SET DEFAULT nextval('chat_messages_id_seq'::regclass);


--
-- TOC entry 2010 (class 2604 OID 92668)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY course_memberships ALTER COLUMN id SET DEFAULT nextval('course_memberships_id_seq'::regclass);


--
-- TOC entry 2001 (class 2604 OID 92592)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY courses ALTER COLUMN id SET DEFAULT nextval('courses_id_seq'::regclass);


--
-- TOC entry 2016 (class 2604 OID 92766)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY courses_users ALTER COLUMN id SET DEFAULT nextval('courses_users_id_seq'::regclass);


--
-- TOC entry 2018 (class 2604 OID 92802)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY email_messages ALTER COLUMN id SET DEFAULT nextval('email_messages_id_seq'::regclass);


--
-- TOC entry 2006 (class 2604 OID 92645)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY exercises ALTER COLUMN id SET DEFAULT nextval('exercises_id_seq'::regclass);


--
-- TOC entry 2005 (class 2604 OID 92627)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups ALTER COLUMN id SET DEFAULT nextval('groups_id_seq'::regclass);


--
-- TOC entry 2015 (class 2604 OID 92748)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups_students ALTER COLUMN id SET DEFAULT nextval('groups_students_id_seq'::regclass);


--
-- TOC entry 2002 (class 2604 OID 92600)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY students ALTER COLUMN id SET DEFAULT nextval('students_id_seq'::regclass);


--
-- TOC entry 2003 (class 2604 OID 92613)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- TOC entry 2081 (class 0 OID 92723)
-- Dependencies: 185
-- Data for Name: action_comments; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO action_comments VALUES (1, 1, 2, 'No on tuo melko hyvä..', '2012-11-22 12:03:26.42+02');
INSERT INTO action_comments VALUES (2, 1, 4, 'Voisi olla tarkempi :)', '2012-11-22 12:03:26.42+02');
INSERT INTO action_comments VALUES (3, 3, 4, 'Ei vastannut edes posteihin...', '2012-11-22 12:03:26.42+02');
INSERT INTO action_comments VALUES (4, 5, 2, 'Tää on melko kärttyinen muutenkin!', '2012-11-22 12:03:26.42+02');


--
-- TOC entry 2078 (class 0 OID 92655)
-- Dependencies: 179
-- Data for Name: action_types; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO action_types VALUES (1, 'Korjauspyyntö', true, true);
INSERT INTO action_types VALUES (2, 'Hylkäys', false, true);
INSERT INTO action_types VALUES (3, 'Huomautus', false, true);
INSERT INTO action_types VALUES (4, 'Lisäaika', true, true);
INSERT INTO action_types VALUES (5, 'Tekemättä', false, false);


--
-- TOC entry 2080 (class 0 OID 92691)
-- Dependencies: 183
-- Data for Name: actions; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO actions VALUES (1, 4, 4, 3, 'Arviointi voisi olla tarkempi', '2012-11-22 12:03:26.42+02', NULL, NULL, NULL, NULL);
INSERT INTO actions VALUES (2, 4, 4, 1, 'Epätarkka arvionti, parempi vaaditaan', '2012-11-22 12:03:26.42+02', NULL, '2013-02-10 00:00:00+02', NULL, NULL);
INSERT INTO actions VALUES (4, 2, 2, 4, 'Flunssassa, lisäaikaa annettu', '2012-11-22 12:03:26.42+02', '2013-01-10 22:22:00+02', '2013-01-14 23:00:00+02', NULL, NULL);
INSERT INTO actions VALUES (5, 3, 5, 3, 'Rakentavampi arviointi olisi paikallaan', '2012-11-22 12:03:26.42+02', NULL, NULL, 3, '2013-02-14 20:59:18.434+02');
INSERT INTO actions VALUES (3, 4, 4, 2, 'Ei tehnyt tehtävää huomautuksesta huolimatta, muutos', '2013-02-10 16:24:01+02', '2013-02-14 21:04:01+02', NULL, NULL, NULL);


--
-- TOC entry 2084 (class 0 OID 92781)
-- Dependencies: 191
-- Data for Name: actions_exercises; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO actions_exercises VALUES (1, 1, 2);
INSERT INTO actions_exercises VALUES (2, 2, 3);
INSERT INTO actions_exercises VALUES (4, 4, 2);
INSERT INTO actions_exercises VALUES (5, 5, 2);
INSERT INTO actions_exercises VALUES (6, 5, 3);
INSERT INTO actions_exercises VALUES (7, 3, 3);


--
-- TOC entry 2086 (class 0 OID 92820)
-- Dependencies: 195
-- Data for Name: chat_messages; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2079 (class 0 OID 92665)
-- Dependencies: 181
-- Data for Name: course_memberships; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO course_memberships VALUES (1, 2, 1, 2, '2013-02-14 20:59:18.434+02', '');
INSERT INTO course_memberships VALUES (2, 2, 2, NULL, NULL, '');
INSERT INTO course_memberships VALUES (3, 2, 3, NULL, NULL, '');
INSERT INTO course_memberships VALUES (4, 2, 4, NULL, NULL, '');
INSERT INTO course_memberships VALUES (5, 2, 5, NULL, NULL, 'Yksinhuoltaja sorsa');


--
-- TOC entry 2073 (class 0 OID 92589)
-- Dependencies: 169
-- Data for Name: courses; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO courses VALUES (1, 'T3 K2012', '2012-01-02 23:00:00+02', '2012-08-31 01:00:00+03');
INSERT INTO courses VALUES (2, 'T3 S2012', '2012-10-21 00:00:00+03', '2013-02-28 00:00:00+02');


--
-- TOC entry 2083 (class 0 OID 92763)
-- Dependencies: 189
-- Data for Name: courses_users; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO courses_users VALUES (1, 1, 1);
INSERT INTO courses_users VALUES (2, 1, 2);
INSERT INTO courses_users VALUES (3, 1, 3);
INSERT INTO courses_users VALUES (4, 1, 4);
INSERT INTO courses_users VALUES (5, 2, 1);
INSERT INTO courses_users VALUES (6, 2, 2);
INSERT INTO courses_users VALUES (7, 2, 3);
INSERT INTO courses_users VALUES (8, 2, 4);


--
-- TOC entry 2085 (class 0 OID 92799)
-- Dependencies: 193
-- Data for Name: email_messages; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2077 (class 0 OID 92642)
-- Dependencies: 177
-- Data for Name: exercises; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO exercises VALUES (1, 1, 1, 'Eka harkka', '2012-08-15 01:00:00+03', '2012-08-20 01:00:00+03', '2012-08-20 01:00:00+03', '2012-08-25 01:00:00+03');
INSERT INTO exercises VALUES (2, 2, 1, 'Eka harkka (K2)', '2013-01-10 00:00:00+02', '2013-01-13 00:00:00+02', '2013-01-12 00:00:00+02', '2013-01-15 00:00:00+02');
INSERT INTO exercises VALUES (3, 2, 2, 'Toka harkka', '2013-02-05 00:00:00+02', '2013-02-10 00:00:00+02', '2013-02-09 00:00:00+02', '2013-02-15 00:00:00+02');
INSERT INTO exercises VALUES (4, 2, 3, 'Kolmas harkka', '2013-02-15 00:00:00+02', '2013-02-18 00:00:00+02', '2013-02-17 00:00:00+02', '2013-02-21 00:00:00+02');


--
-- TOC entry 2076 (class 0 OID 92624)
-- Dependencies: 175
-- Data for Name: groups; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO groups VALUES (1, 2, 2);
INSERT INTO groups VALUES (2, 2, 3);
INSERT INTO groups VALUES (3, 2, 4);


--
-- TOC entry 2082 (class 0 OID 92745)
-- Dependencies: 187
-- Data for Name: groups_students; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO groups_students VALUES (1, 1, 1);
INSERT INTO groups_students VALUES (2, 1, 2);
INSERT INTO groups_students VALUES (3, 2, 3);
INSERT INTO groups_students VALUES (4, 3, 4);
INSERT INTO groups_students VALUES (5, 2, 5);


--
-- TOC entry 2074 (class 0 OID 92597)
-- Dependencies: 171
-- Data for Name: students; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO students VALUES (1, 34243, 'Testaaja', 'Testi', 'testi.testaaja@uta.fi');
INSERT INTO students VALUES (2, 12345, 'Opiskelija', 'Ossi', 'ossi.opiskelija@uta.fi');
INSERT INTO students VALUES (3, 98765, 'Mänty', 'Jarmo', 'jarmo.manty@uta.fi');
INSERT INTO students VALUES (4, 43214, 'Luttinen', 'Usko', 'usko.luttinen@uta.fi');
INSERT INTO students VALUES (5, 26371, 'Sorsa', 'Pulla', 'pulla.sorsa@uta.fi');


--
-- TOC entry 2075 (class 0 OID 92610)
-- Dependencies: 173
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO users VALUES (1, '12345', 'Opettaja', 'Vastuu', 'vastuuope@uta.fi', 'ce192a5d433c20a11b143e63319f960f3a2361b8', true, NULL);
INSERT INTO users VALUES (2, '23456', 'Assistentti', 'Asseri', 'asseri.assistentti@uta.fi', 'ce192a5d433c20a11b143e63319f960f3a2361b8', false, NULL);
INSERT INTO users VALUES (3, '34567', 'Assistentti', 'Testi', 'testi.assistentti@uta.fi', 'ce192a5d433c20a11b143e63319f960f3a2361b8', false, NULL);
INSERT INTO users VALUES (4, '45678', 'Auttaja', 'Aapo', 'aapo.auttaja@uta.fi', 'ce192a5d433c20a11b143e63319f960f3a2361b8', false, NULL);


--
-- TOC entry 2041 (class 2606 OID 92732)
-- Name: action_comments_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY action_comments
    ADD CONSTRAINT action_comments_pkey PRIMARY KEY (id);


--
-- TOC entry 2035 (class 2606 OID 92662)
-- Name: action_types_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY action_types
    ADD CONSTRAINT action_types_pkey PRIMARY KEY (id);


--
-- TOC entry 2047 (class 2606 OID 92786)
-- Name: actions_exercises_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY actions_exercises
    ADD CONSTRAINT actions_exercises_pkey PRIMARY KEY (id);


--
-- TOC entry 2039 (class 2606 OID 92700)
-- Name: actions_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_pkey PRIMARY KEY (id);


--
-- TOC entry 2051 (class 2606 OID 92828)
-- Name: chat_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY chat_messages
    ADD CONSTRAINT chat_messages_pkey PRIMARY KEY (id);


--
-- TOC entry 2037 (class 2606 OID 92673)
-- Name: course_memberships_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY course_memberships
    ADD CONSTRAINT course_memberships_pkey PRIMARY KEY (id);


--
-- TOC entry 2021 (class 2606 OID 92594)
-- Name: courses_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY courses
    ADD CONSTRAINT courses_pkey PRIMARY KEY (id);


--
-- TOC entry 2045 (class 2606 OID 92768)
-- Name: courses_users_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY courses_users
    ADD CONSTRAINT courses_users_pkey PRIMARY KEY (id);


--
-- TOC entry 2049 (class 2606 OID 92807)
-- Name: email_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY email_messages
    ADD CONSTRAINT email_messages_pkey PRIMARY KEY (id);


--
-- TOC entry 2033 (class 2606 OID 92647)
-- Name: exercises_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exercises
    ADD CONSTRAINT exercises_pkey PRIMARY KEY (id);


--
-- TOC entry 2031 (class 2606 OID 92629)
-- Name: groups_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_pkey PRIMARY KEY (id);


--
-- TOC entry 2043 (class 2606 OID 92750)
-- Name: groups_students_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY groups_students
    ADD CONSTRAINT groups_students_pkey PRIMARY KEY (id);


--
-- TOC entry 2023 (class 2606 OID 92605)
-- Name: students_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY students
    ADD CONSTRAINT students_pkey PRIMARY KEY (id);


--
-- TOC entry 2025 (class 2606 OID 92607)
-- Name: students_student_number_key; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY students
    ADD CONSTRAINT students_student_number_key UNIQUE (student_number);


--
-- TOC entry 2027 (class 2606 OID 92621)
-- Name: users_basic_user_account_key; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_basic_user_account_key UNIQUE (basic_user_account);


--
-- TOC entry 2029 (class 2606 OID 92619)
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 2062 (class 2606 OID 92733)
-- Name: action_comments_action_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY action_comments
    ADD CONSTRAINT action_comments_action_id_fkey FOREIGN KEY (action_id) REFERENCES actions(id);


--
-- TOC entry 2063 (class 2606 OID 92738)
-- Name: action_comments_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY action_comments
    ADD CONSTRAINT action_comments_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 2060 (class 2606 OID 92711)
-- Name: actions_action_type_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_action_type_id_fkey FOREIGN KEY (action_type_id) REFERENCES action_types(id);


--
-- TOC entry 2059 (class 2606 OID 92706)
-- Name: actions_course_membership_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_course_membership_id_fkey FOREIGN KEY (course_membership_id) REFERENCES course_memberships(id);


--
-- TOC entry 2068 (class 2606 OID 92787)
-- Name: actions_exercises_action_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions_exercises
    ADD CONSTRAINT actions_exercises_action_id_fkey FOREIGN KEY (action_id) REFERENCES actions(id);


--
-- TOC entry 2069 (class 2606 OID 92792)
-- Name: actions_exercises_exercise_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions_exercises
    ADD CONSTRAINT actions_exercises_exercise_id_fkey FOREIGN KEY (exercise_id) REFERENCES exercises(id);


--
-- TOC entry 2061 (class 2606 OID 92716)
-- Name: actions_handled_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_handled_id_fkey FOREIGN KEY (handled_id) REFERENCES users(id);


--
-- TOC entry 2058 (class 2606 OID 92701)
-- Name: actions_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 2072 (class 2606 OID 92829)
-- Name: chat_messages_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY chat_messages
    ADD CONSTRAINT chat_messages_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 2055 (class 2606 OID 92674)
-- Name: course_memberships_course_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY course_memberships
    ADD CONSTRAINT course_memberships_course_id_fkey FOREIGN KEY (course_id) REFERENCES courses(id);


--
-- TOC entry 2057 (class 2606 OID 92684)
-- Name: course_memberships_quit_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY course_memberships
    ADD CONSTRAINT course_memberships_quit_id_fkey FOREIGN KEY (quit_id) REFERENCES users(id);


--
-- TOC entry 2056 (class 2606 OID 92679)
-- Name: course_memberships_student_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY course_memberships
    ADD CONSTRAINT course_memberships_student_id_fkey FOREIGN KEY (student_id) REFERENCES students(id);


--
-- TOC entry 2066 (class 2606 OID 92769)
-- Name: courses_users_course_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY courses_users
    ADD CONSTRAINT courses_users_course_id_fkey FOREIGN KEY (course_id) REFERENCES courses(id);


--
-- TOC entry 2067 (class 2606 OID 92774)
-- Name: courses_users_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY courses_users
    ADD CONSTRAINT courses_users_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 2071 (class 2606 OID 92813)
-- Name: email_messages_action_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY email_messages
    ADD CONSTRAINT email_messages_action_id_fkey FOREIGN KEY (action_id) REFERENCES actions(id);


--
-- TOC entry 2070 (class 2606 OID 92808)
-- Name: email_messages_course_membership_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY email_messages
    ADD CONSTRAINT email_messages_course_membership_id_fkey FOREIGN KEY (course_membership_id) REFERENCES course_memberships(id);


--
-- TOC entry 2054 (class 2606 OID 92648)
-- Name: exercises_course_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY exercises
    ADD CONSTRAINT exercises_course_id_fkey FOREIGN KEY (course_id) REFERENCES courses(id);


--
-- TOC entry 2052 (class 2606 OID 92630)
-- Name: groups_course_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_course_id_fkey FOREIGN KEY (course_id) REFERENCES courses(id);


--
-- TOC entry 2064 (class 2606 OID 92751)
-- Name: groups_students_group_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups_students
    ADD CONSTRAINT groups_students_group_id_fkey FOREIGN KEY (group_id) REFERENCES groups(id);


--
-- TOC entry 2065 (class 2606 OID 92756)
-- Name: groups_students_student_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups_students
    ADD CONSTRAINT groups_students_student_id_fkey FOREIGN KEY (student_id) REFERENCES students(id);


--
-- TOC entry 2053 (class 2606 OID 92635)
-- Name: groups_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 2093 (class 0 OID 0)
-- Dependencies: 6
-- Name: public; Type: ACL; Schema: -; Owner: -
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2013-02-14 21:06:34

--
-- PostgreSQL database dump complete
--

