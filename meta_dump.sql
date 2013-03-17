--
-- PostgreSQL database dump
--

-- Dumped from database version 9.2.3
-- Dumped by pg_dump version 9.2.3
-- Started on 2013-03-17 19:05:38

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;



--
-- TOC entry 196 (class 3079 OID 11727)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2109 (class 0 OID 0)
-- Dependencies: 196
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 168 (class 1259 OID 16385)
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
-- TOC entry 169 (class 1259 OID 16392)
-- Name: action_comments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE action_comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2110 (class 0 OID 0)
-- Dependencies: 169
-- Name: action_comments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE action_comments_id_seq OWNED BY action_comments.id;


--
-- TOC entry 170 (class 1259 OID 16394)
-- Name: action_email_templates; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE action_email_templates (
    id integer NOT NULL,
    action_type_id integer NOT NULL,
    subject character varying(255) NOT NULL,
    content text NOT NULL
);


--
-- TOC entry 171 (class 1259 OID 16400)
-- Name: action_email_templates_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE action_email_templates_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2111 (class 0 OID 0)
-- Dependencies: 171
-- Name: action_email_templates_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE action_email_templates_id_seq OWNED BY action_email_templates.id;


--
-- TOC entry 172 (class 1259 OID 16402)
-- Name: action_types; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE action_types (
    id integer NOT NULL,
    name character varying(50),
    require_deadline boolean DEFAULT false NOT NULL,
    active boolean DEFAULT true NOT NULL
);


--
-- TOC entry 173 (class 1259 OID 16407)
-- Name: action_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE action_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2112 (class 0 OID 0)
-- Dependencies: 173
-- Name: action_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE action_types_id_seq OWNED BY action_types.id;


--
-- TOC entry 174 (class 1259 OID 16409)
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
-- TOC entry 175 (class 1259 OID 16416)
-- Name: actions_exercises; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE actions_exercises (
    id integer NOT NULL,
    action_id integer NOT NULL,
    exercise_id integer NOT NULL
);


--
-- TOC entry 176 (class 1259 OID 16419)
-- Name: actions_exercises_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE actions_exercises_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2113 (class 0 OID 0)
-- Dependencies: 176
-- Name: actions_exercises_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE actions_exercises_id_seq OWNED BY actions_exercises.id;


--
-- TOC entry 177 (class 1259 OID 16421)
-- Name: actions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE actions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2114 (class 0 OID 0)
-- Dependencies: 177
-- Name: actions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE actions_id_seq OWNED BY actions.id;


--
-- TOC entry 178 (class 1259 OID 16423)
-- Name: chat_messages; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE chat_messages (
    id integer NOT NULL,
    user_id integer,
    content text,
    created timestamp with time zone
);


--
-- TOC entry 179 (class 1259 OID 16429)
-- Name: chat_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE chat_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2115 (class 0 OID 0)
-- Dependencies: 179
-- Name: chat_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE chat_messages_id_seq OWNED BY chat_messages.id;


--
-- TOC entry 180 (class 1259 OID 16431)
-- Name: course_memberships; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE course_memberships (
    id integer NOT NULL,
    course_id integer NOT NULL,
    student_id integer NOT NULL,
    quit_id integer,
    quit_time timestamp with time zone,
    comment text,
    group_id integer
);


--
-- TOC entry 181 (class 1259 OID 16437)
-- Name: course_memberships_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE course_memberships_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2116 (class 0 OID 0)
-- Dependencies: 181
-- Name: course_memberships_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE course_memberships_id_seq OWNED BY course_memberships.id;


--
-- TOC entry 182 (class 1259 OID 16439)
-- Name: courses; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE courses (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    starttime timestamp with time zone NOT NULL,
    endtime timestamp with time zone NOT NULL
);


--
-- TOC entry 183 (class 1259 OID 16442)
-- Name: courses_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE courses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2117 (class 0 OID 0)
-- Dependencies: 183
-- Name: courses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE courses_id_seq OWNED BY courses.id;


--
-- TOC entry 184 (class 1259 OID 16444)
-- Name: courses_users; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE courses_users (
    id integer NOT NULL,
    course_id integer NOT NULL,
    user_id integer NOT NULL
);


--
-- TOC entry 185 (class 1259 OID 16447)
-- Name: courses_users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE courses_users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2118 (class 0 OID 0)
-- Dependencies: 185
-- Name: courses_users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE courses_users_id_seq OWNED BY courses_users.id;


--
-- TOC entry 186 (class 1259 OID 16449)
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
-- TOC entry 187 (class 1259 OID 16455)
-- Name: email_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE email_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2119 (class 0 OID 0)
-- Dependencies: 187
-- Name: email_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE email_messages_id_seq OWNED BY email_messages.id;


--
-- TOC entry 188 (class 1259 OID 16457)
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
-- TOC entry 189 (class 1259 OID 16460)
-- Name: exercises_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE exercises_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2120 (class 0 OID 0)
-- Dependencies: 189
-- Name: exercises_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE exercises_id_seq OWNED BY exercises.id;


--
-- TOC entry 190 (class 1259 OID 16462)
-- Name: groups; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE groups (
    id integer NOT NULL,
    course_id integer NOT NULL,
    user_id integer NOT NULL
);


--
-- TOC entry 191 (class 1259 OID 16465)
-- Name: groups_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2121 (class 0 OID 0)
-- Dependencies: 191
-- Name: groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE groups_id_seq OWNED BY groups.id;


--
-- TOC entry 192 (class 1259 OID 16472)
-- Name: students; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE students (
    id integer NOT NULL,
    student_number character varying(30) NOT NULL,
    last_name character varying(255) NOT NULL,
    first_name character varying(255) NOT NULL,
    email character varying(255) NOT NULL
);


--
-- TOC entry 193 (class 1259 OID 16478)
-- Name: students_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE students_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2122 (class 0 OID 0)
-- Dependencies: 193
-- Name: students_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE students_id_seq OWNED BY students.id;


--
-- TOC entry 194 (class 1259 OID 16480)
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
-- TOC entry 195 (class 1259 OID 16487)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2123 (class 0 OID 0)
-- Dependencies: 195
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- TOC entry 2003 (class 2604 OID 16489)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY action_comments ALTER COLUMN id SET DEFAULT nextval('action_comments_id_seq'::regclass);


--
-- TOC entry 2004 (class 2604 OID 16490)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY action_email_templates ALTER COLUMN id SET DEFAULT nextval('action_email_templates_id_seq'::regclass);


--
-- TOC entry 2007 (class 2604 OID 16491)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY action_types ALTER COLUMN id SET DEFAULT nextval('action_types_id_seq'::regclass);


--
-- TOC entry 2009 (class 2604 OID 16492)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions ALTER COLUMN id SET DEFAULT nextval('actions_id_seq'::regclass);


--
-- TOC entry 2010 (class 2604 OID 16493)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions_exercises ALTER COLUMN id SET DEFAULT nextval('actions_exercises_id_seq'::regclass);


--
-- TOC entry 2011 (class 2604 OID 16494)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY chat_messages ALTER COLUMN id SET DEFAULT nextval('chat_messages_id_seq'::regclass);


--
-- TOC entry 2012 (class 2604 OID 16495)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY course_memberships ALTER COLUMN id SET DEFAULT nextval('course_memberships_id_seq'::regclass);


--
-- TOC entry 2013 (class 2604 OID 16496)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY courses ALTER COLUMN id SET DEFAULT nextval('courses_id_seq'::regclass);


--
-- TOC entry 2014 (class 2604 OID 16497)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY courses_users ALTER COLUMN id SET DEFAULT nextval('courses_users_id_seq'::regclass);


--
-- TOC entry 2015 (class 2604 OID 16498)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY email_messages ALTER COLUMN id SET DEFAULT nextval('email_messages_id_seq'::regclass);


--
-- TOC entry 2016 (class 2604 OID 16499)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY exercises ALTER COLUMN id SET DEFAULT nextval('exercises_id_seq'::regclass);


--
-- TOC entry 2017 (class 2604 OID 16500)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups ALTER COLUMN id SET DEFAULT nextval('groups_id_seq'::regclass);


--
-- TOC entry 2018 (class 2604 OID 16502)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY students ALTER COLUMN id SET DEFAULT nextval('students_id_seq'::regclass);


--
-- TOC entry 2020 (class 2604 OID 16503)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- TOC entry 2074 (class 0 OID 16385)
-- Dependencies: 168
-- Data for Name: action_comments; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2124 (class 0 OID 0)
-- Dependencies: 169
-- Name: action_comments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('action_comments_id_seq', 1, false);


--
-- TOC entry 2076 (class 0 OID 16394)
-- Dependencies: 170
-- Data for Name: action_email_templates; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO action_email_templates VALUES (1, 1, 'Korjauspyyntö', 'Lorem ipsum dolor');
INSERT INTO action_email_templates VALUES (3, 2, 'Hylkäys', 'Lorem ipsum dolor');
INSERT INTO action_email_templates VALUES (4, 3, 'Huomautus', 'Lorem ipsum dolor');
INSERT INTO action_email_templates VALUES (5, 4, 'Lisäaika', 'Lorem ipsum dolor');
INSERT INTO action_email_templates VALUES (6, 5, 'Tekemättä', 'Lorem ipsum dolor');


--
-- TOC entry 2125 (class 0 OID 0)
-- Dependencies: 171
-- Name: action_email_templates_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('action_email_templates_id_seq', 6, true);


--
-- TOC entry 2078 (class 0 OID 16402)
-- Dependencies: 172
-- Data for Name: action_types; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO action_types VALUES (1, 'Korjauspyyntö', true, true);
INSERT INTO action_types VALUES (2, 'Hylkäys', false, true);
INSERT INTO action_types VALUES (3, 'Huomautus', false, true);
INSERT INTO action_types VALUES (4, 'Lisäaika', true, true);
INSERT INTO action_types VALUES (5, 'Tekemättä', false, false);


--
-- TOC entry 2126 (class 0 OID 0)
-- Dependencies: 173
-- Name: action_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('action_types_id_seq', 5, true);


--
-- TOC entry 2080 (class 0 OID 16409)
-- Dependencies: 174
-- Data for Name: actions; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2081 (class 0 OID 16416)
-- Dependencies: 175
-- Data for Name: actions_exercises; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2127 (class 0 OID 0)
-- Dependencies: 176
-- Name: actions_exercises_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('actions_exercises_id_seq', 1, false);


--
-- TOC entry 2128 (class 0 OID 0)
-- Dependencies: 177
-- Name: actions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('actions_id_seq', 1, false);


--
-- TOC entry 2084 (class 0 OID 16423)
-- Dependencies: 178
-- Data for Name: chat_messages; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2129 (class 0 OID 0)
-- Dependencies: 179
-- Name: chat_messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('chat_messages_id_seq', 1, false);


--
-- TOC entry 2086 (class 0 OID 16431)
-- Dependencies: 180
-- Data for Name: course_memberships; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2130 (class 0 OID 0)
-- Dependencies: 181
-- Name: course_memberships_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('course_memberships_id_seq', 1, false);


--
-- TOC entry 2088 (class 0 OID 16439)
-- Dependencies: 182
-- Data for Name: courses; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2131 (class 0 OID 0)
-- Dependencies: 183
-- Name: courses_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('courses_id_seq', 1, false);


--
-- TOC entry 2090 (class 0 OID 16444)
-- Dependencies: 184
-- Data for Name: courses_users; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2132 (class 0 OID 0)
-- Dependencies: 185
-- Name: courses_users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('courses_users_id_seq', 1, false);


--
-- TOC entry 2092 (class 0 OID 16449)
-- Dependencies: 186
-- Data for Name: email_messages; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2133 (class 0 OID 0)
-- Dependencies: 187
-- Name: email_messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('email_messages_id_seq', 1, false);


--
-- TOC entry 2094 (class 0 OID 16457)
-- Dependencies: 188
-- Data for Name: exercises; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2134 (class 0 OID 0)
-- Dependencies: 189
-- Name: exercises_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('exercises_id_seq', 1, false);


--
-- TOC entry 2096 (class 0 OID 16462)
-- Dependencies: 190
-- Data for Name: groups; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2135 (class 0 OID 0)
-- Dependencies: 191
-- Name: groups_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('groups_id_seq', 1, false);


--
-- TOC entry 2098 (class 0 OID 16472)
-- Dependencies: 192
-- Data for Name: students; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 2136 (class 0 OID 0)
-- Dependencies: 193
-- Name: students_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('students_id_seq', 1, false);


--
-- TOC entry 2100 (class 0 OID 16480)
-- Dependencies: 194
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO users VALUES (3, '34567', 'Assistentti', 'Testi', 'testi.assistentti@uta.fi', 'ce192a5d433c20a11b143e63319f960f3a2361b8', false, NULL);
INSERT INTO users VALUES (4, '45678', 'Auttaja', 'Aapo', 'aapo.auttaja@uta.fi', 'ce192a5d433c20a11b143e63319f960f3a2361b8', false, NULL);
INSERT INTO users VALUES (2, '23456', 'Assistentti', 'Asseri', 'asseri.assistentti@uta.fi', 'ce192a5d433c20a11b143e63319f960f3a2361b8', false, '2013-03-12 19:05:34+02');
INSERT INTO users VALUES (1, '12345', 'Opettaja', 'Vastuu', 'vastuuope@uta.fi', 'ce192a5d433c20a11b143e63319f960f3a2361b8', true, '2013-03-14 19:14:44+02');


--
-- TOC entry 2137 (class 0 OID 0)
-- Dependencies: 195
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('users_id_seq', 4, true);


--
-- TOC entry 2022 (class 2606 OID 16505)
-- Name: action_comments_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY action_comments
    ADD CONSTRAINT action_comments_pkey PRIMARY KEY (id);


--
-- TOC entry 2024 (class 2606 OID 16507)
-- Name: action_email_templates_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY action_email_templates
    ADD CONSTRAINT action_email_templates_pkey PRIMARY KEY (id);


--
-- TOC entry 2026 (class 2606 OID 16509)
-- Name: action_types_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY action_types
    ADD CONSTRAINT action_types_pkey PRIMARY KEY (id);


--
-- TOC entry 2030 (class 2606 OID 16511)
-- Name: actions_exercises_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY actions_exercises
    ADD CONSTRAINT actions_exercises_pkey PRIMARY KEY (id);


--
-- TOC entry 2028 (class 2606 OID 16513)
-- Name: actions_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_pkey PRIMARY KEY (id);


--
-- TOC entry 2032 (class 2606 OID 16515)
-- Name: chat_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY chat_messages
    ADD CONSTRAINT chat_messages_pkey PRIMARY KEY (id);


--
-- TOC entry 2034 (class 2606 OID 16517)
-- Name: course_memberships_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY course_memberships
    ADD CONSTRAINT course_memberships_pkey PRIMARY KEY (id);


--
-- TOC entry 2036 (class 2606 OID 16519)
-- Name: courses_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY courses
    ADD CONSTRAINT courses_pkey PRIMARY KEY (id);


--
-- TOC entry 2038 (class 2606 OID 16521)
-- Name: courses_users_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY courses_users
    ADD CONSTRAINT courses_users_pkey PRIMARY KEY (id);


--
-- TOC entry 2040 (class 2606 OID 16523)
-- Name: email_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY email_messages
    ADD CONSTRAINT email_messages_pkey PRIMARY KEY (id);


--
-- TOC entry 2042 (class 2606 OID 16525)
-- Name: exercises_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exercises
    ADD CONSTRAINT exercises_pkey PRIMARY KEY (id);


--
-- TOC entry 2044 (class 2606 OID 16527)
-- Name: groups_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_pkey PRIMARY KEY (id);


--
-- TOC entry 2046 (class 2606 OID 16531)
-- Name: students_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY students
    ADD CONSTRAINT students_pkey PRIMARY KEY (id);


--
-- TOC entry 2048 (class 2606 OID 16533)
-- Name: students_student_number_key; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY students
    ADD CONSTRAINT students_student_number_key UNIQUE (student_number);


--
-- TOC entry 2050 (class 2606 OID 16535)
-- Name: users_basic_user_account_key; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_basic_user_account_key UNIQUE (basic_user_account);


--
-- TOC entry 2052 (class 2606 OID 16537)
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 2053 (class 2606 OID 16538)
-- Name: action_comments_action_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY action_comments
    ADD CONSTRAINT action_comments_action_id_fkey FOREIGN KEY (action_id) REFERENCES actions(id);


--
-- TOC entry 2054 (class 2606 OID 16543)
-- Name: action_comments_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY action_comments
    ADD CONSTRAINT action_comments_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 2055 (class 2606 OID 16548)
-- Name: action_email_templates_action_type_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY action_email_templates
    ADD CONSTRAINT action_email_templates_action_type_id_fkey FOREIGN KEY (action_type_id) REFERENCES action_types(id);


--
-- TOC entry 2056 (class 2606 OID 16553)
-- Name: actions_action_type_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_action_type_id_fkey FOREIGN KEY (action_type_id) REFERENCES action_types(id);


--
-- TOC entry 2057 (class 2606 OID 16558)
-- Name: actions_course_membership_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_course_membership_id_fkey FOREIGN KEY (course_membership_id) REFERENCES course_memberships(id);


--
-- TOC entry 2060 (class 2606 OID 16563)
-- Name: actions_exercises_action_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions_exercises
    ADD CONSTRAINT actions_exercises_action_id_fkey FOREIGN KEY (action_id) REFERENCES actions(id);


--
-- TOC entry 2061 (class 2606 OID 16568)
-- Name: actions_exercises_exercise_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions_exercises
    ADD CONSTRAINT actions_exercises_exercise_id_fkey FOREIGN KEY (exercise_id) REFERENCES exercises(id);


--
-- TOC entry 2058 (class 2606 OID 16573)
-- Name: actions_handled_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_handled_id_fkey FOREIGN KEY (handled_id) REFERENCES users(id);


--
-- TOC entry 2059 (class 2606 OID 16578)
-- Name: actions_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 2062 (class 2606 OID 16583)
-- Name: chat_messages_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY chat_messages
    ADD CONSTRAINT chat_messages_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 2063 (class 2606 OID 16588)
-- Name: course_memberships_course_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY course_memberships
    ADD CONSTRAINT course_memberships_course_id_fkey FOREIGN KEY (course_id) REFERENCES courses(id);


--
-- TOC entry 2066 (class 2606 OID 16650)
-- Name: course_memberships_group_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY course_memberships
    ADD CONSTRAINT course_memberships_group_id_fkey FOREIGN KEY (group_id) REFERENCES groups(id);


--
-- TOC entry 2064 (class 2606 OID 16593)
-- Name: course_memberships_quit_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY course_memberships
    ADD CONSTRAINT course_memberships_quit_id_fkey FOREIGN KEY (quit_id) REFERENCES users(id);


--
-- TOC entry 2065 (class 2606 OID 16598)
-- Name: course_memberships_student_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY course_memberships
    ADD CONSTRAINT course_memberships_student_id_fkey FOREIGN KEY (student_id) REFERENCES students(id);


--
-- TOC entry 2067 (class 2606 OID 16603)
-- Name: courses_users_course_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY courses_users
    ADD CONSTRAINT courses_users_course_id_fkey FOREIGN KEY (course_id) REFERENCES courses(id);


--
-- TOC entry 2068 (class 2606 OID 16608)
-- Name: courses_users_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY courses_users
    ADD CONSTRAINT courses_users_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 2069 (class 2606 OID 16613)
-- Name: email_messages_action_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY email_messages
    ADD CONSTRAINT email_messages_action_id_fkey FOREIGN KEY (action_id) REFERENCES actions(id);


--
-- TOC entry 2070 (class 2606 OID 16618)
-- Name: email_messages_course_membership_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY email_messages
    ADD CONSTRAINT email_messages_course_membership_id_fkey FOREIGN KEY (course_membership_id) REFERENCES course_memberships(id);


--
-- TOC entry 2071 (class 2606 OID 16623)
-- Name: exercises_course_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY exercises
    ADD CONSTRAINT exercises_course_id_fkey FOREIGN KEY (course_id) REFERENCES courses(id);


--
-- TOC entry 2072 (class 2606 OID 16628)
-- Name: groups_course_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_course_id_fkey FOREIGN KEY (course_id) REFERENCES courses(id);


--
-- TOC entry 2073 (class 2606 OID 16643)
-- Name: groups_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 2108 (class 0 OID 0)
-- Dependencies: 6
-- Name: public; Type: ACL; Schema: -; Owner: -
--

--REVOKE ALL ON SCHEMA public FROM PUBLIC;
--REVOKE ALL ON SCHEMA public FROM postgres;
--GRANT ALL ON SCHEMA public TO postgres;
--GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2013-03-17 19:05:39

--
-- PostgreSQL database dump complete
--

