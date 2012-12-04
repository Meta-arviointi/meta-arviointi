--
-- PostgreSQL database dump
--

-- Dumped from database version 9.2.0
-- Dumped by pg_dump version 9.2.0
-- Started on 2012-12-02 19:59:30

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;


CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 183 (class 1259 OID 66466)
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
-- TOC entry 182 (class 1259 OID 66464)
-- Name: action_comments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE action_comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2053 (class 0 OID 0)
-- Dependencies: 182
-- Name: action_comments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE action_comments_id_seq OWNED BY action_comments.id;


--
-- TOC entry 2054 (class 0 OID 0)
-- Dependencies: 182
-- Name: action_comments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('action_comments_id_seq', 4, true);


--
-- TOC entry 179 (class 1259 OID 66424)
-- Name: action_types; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE action_types (
    id integer NOT NULL,
    name character varying(50)
);


--
-- TOC entry 178 (class 1259 OID 66422)
-- Name: action_types_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE action_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2055 (class 0 OID 0)
-- Dependencies: 178
-- Name: action_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE action_types_id_seq OWNED BY action_types.id;


--
-- TOC entry 2056 (class 0 OID 0)
-- Dependencies: 178
-- Name: action_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('action_types_id_seq', 4, true);


--
-- TOC entry 181 (class 1259 OID 66432)
-- Name: actions; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE actions (
    id integer NOT NULL,
    user_id integer NOT NULL,
    student_id integer NOT NULL,
    exercise_id integer NOT NULL,
    action_type_id integer NOT NULL,
    description character varying(255),
    created timestamp with time zone DEFAULT now() NOT NULL,
    deadline timestamp with time zone,
    handled_id integer,
    handled_time timestamp with time zone
);


--
-- TOC entry 180 (class 1259 OID 66430)
-- Name: actions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE actions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2057 (class 0 OID 0)
-- Dependencies: 180
-- Name: actions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE actions_id_seq OWNED BY actions.id;


--
-- TOC entry 2058 (class 0 OID 0)
-- Dependencies: 180
-- Name: actions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('actions_id_seq', 5, true);


--
-- TOC entry 187 (class 1259 OID 66506)
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
-- TOC entry 186 (class 1259 OID 66504)
-- Name: course_memberships_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE course_memberships_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2059 (class 0 OID 0)
-- Dependencies: 186
-- Name: course_memberships_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE course_memberships_id_seq OWNED BY course_memberships.id;


--
-- TOC entry 2060 (class 0 OID 0)
-- Dependencies: 186
-- Name: course_memberships_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('course_memberships_id_seq', 5, true);


--
-- TOC entry 169 (class 1259 OID 66358)
-- Name: courses; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE courses (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    starttime timestamp with time zone NOT NULL,
    endtime timestamp with time zone NOT NULL
);


--
-- TOC entry 168 (class 1259 OID 66356)
-- Name: courses_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE courses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2061 (class 0 OID 0)
-- Dependencies: 168
-- Name: courses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE courses_id_seq OWNED BY courses.id;


--
-- TOC entry 2062 (class 0 OID 0)
-- Dependencies: 168
-- Name: courses_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('courses_id_seq', 2, true);


--
-- TOC entry 189 (class 1259 OID 66532)
-- Name: courses_users; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE courses_users (
    id integer NOT NULL,
    course_id integer NOT NULL,
    user_id integer NOT NULL
);


--
-- TOC entry 188 (class 1259 OID 66530)
-- Name: courses_users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE courses_users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2063 (class 0 OID 0)
-- Dependencies: 188
-- Name: courses_users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE courses_users_id_seq OWNED BY courses_users.id;


--
-- TOC entry 2064 (class 0 OID 0)
-- Dependencies: 188
-- Name: courses_users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('courses_users_id_seq', 8, true);


--
-- TOC entry 177 (class 1259 OID 66411)
-- Name: exercises; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE exercises (
    id integer NOT NULL,
    course_id integer NOT NULL,
    exercise_number integer NOT NULL,
    deadline timestamp with time zone NOT NULL
);


--
-- TOC entry 176 (class 1259 OID 66409)
-- Name: exercises_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE exercises_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2065 (class 0 OID 0)
-- Dependencies: 176
-- Name: exercises_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE exercises_id_seq OWNED BY exercises.id;


--
-- TOC entry 2066 (class 0 OID 0)
-- Dependencies: 176
-- Name: exercises_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('exercises_id_seq', 4, true);


--
-- TOC entry 175 (class 1259 OID 66393)
-- Name: groups; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE groups (
    id integer NOT NULL,
    course_id integer NOT NULL,
    user_id integer NOT NULL
);


--
-- TOC entry 174 (class 1259 OID 66391)
-- Name: groups_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2067 (class 0 OID 0)
-- Dependencies: 174
-- Name: groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE groups_id_seq OWNED BY groups.id;


--
-- TOC entry 2068 (class 0 OID 0)
-- Dependencies: 174
-- Name: groups_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('groups_id_seq', 3, true);


--
-- TOC entry 185 (class 1259 OID 66488)
-- Name: groups_students; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE groups_students (
    id integer NOT NULL,
    group_id integer NOT NULL,
    student_id integer NOT NULL
);


--
-- TOC entry 184 (class 1259 OID 66486)
-- Name: groups_students_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE groups_students_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2069 (class 0 OID 0)
-- Dependencies: 184
-- Name: groups_students_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE groups_students_id_seq OWNED BY groups_students.id;


--
-- TOC entry 2070 (class 0 OID 0)
-- Dependencies: 184
-- Name: groups_students_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('groups_students_id_seq', 5, true);


--
-- TOC entry 171 (class 1259 OID 66366)
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
-- TOC entry 170 (class 1259 OID 66364)
-- Name: students_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE students_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2071 (class 0 OID 0)
-- Dependencies: 170
-- Name: students_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE students_id_seq OWNED BY students.id;


--
-- TOC entry 2072 (class 0 OID 0)
-- Dependencies: 170
-- Name: students_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('students_id_seq', 5, true);


--
-- TOC entry 173 (class 1259 OID 66379)
-- Name: users; Type: TABLE; Schema: public; Owner: -; Tablespace: 
--

CREATE TABLE users (
    id integer NOT NULL,
    basic_user_account character varying(30),
    last_name character varying(255) NOT NULL,
    first_name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(50) NOT NULL,
    is_admin boolean DEFAULT false NOT NULL
);


--
-- TOC entry 172 (class 1259 OID 66377)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 2073 (class 0 OID 0)
-- Dependencies: 172
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- TOC entry 2074 (class 0 OID 0)
-- Dependencies: 172
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('users_id_seq', 4, true);


--
-- TOC entry 1987 (class 2604 OID 66469)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY action_comments ALTER COLUMN id SET DEFAULT nextval('action_comments_id_seq'::regclass);


--
-- TOC entry 1984 (class 2604 OID 66427)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY action_types ALTER COLUMN id SET DEFAULT nextval('action_types_id_seq'::regclass);


--
-- TOC entry 1985 (class 2604 OID 66435)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions ALTER COLUMN id SET DEFAULT nextval('actions_id_seq'::regclass);


--
-- TOC entry 1990 (class 2604 OID 66509)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY course_memberships ALTER COLUMN id SET DEFAULT nextval('course_memberships_id_seq'::regclass);


--
-- TOC entry 1978 (class 2604 OID 66361)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY courses ALTER COLUMN id SET DEFAULT nextval('courses_id_seq'::regclass);


--
-- TOC entry 1991 (class 2604 OID 66535)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY courses_users ALTER COLUMN id SET DEFAULT nextval('courses_users_id_seq'::regclass);


--
-- TOC entry 1983 (class 2604 OID 66414)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY exercises ALTER COLUMN id SET DEFAULT nextval('exercises_id_seq'::regclass);


--
-- TOC entry 1982 (class 2604 OID 66396)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups ALTER COLUMN id SET DEFAULT nextval('groups_id_seq'::regclass);


--
-- TOC entry 1989 (class 2604 OID 66491)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups_students ALTER COLUMN id SET DEFAULT nextval('groups_students_id_seq'::regclass);


--
-- TOC entry 1979 (class 2604 OID 66369)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY students ALTER COLUMN id SET DEFAULT nextval('students_id_seq'::regclass);


--
-- TOC entry 1980 (class 2604 OID 66382)
-- Name: id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- TOC entry 2042 (class 0 OID 66466)
-- Dependencies: 183
-- Data for Name: action_comments; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO action_comments VALUES (1, 1, 2, 'No on tuo melko hyvä..', '2012-11-22 12:03:26.42+02');
INSERT INTO action_comments VALUES (2, 1, 4, 'Voisi olla tarkempi :)', '2012-11-22 12:03:26.42+02');
INSERT INTO action_comments VALUES (3, 3, 4, 'Ei vastannut edes posteihin...', '2012-11-22 12:03:26.42+02');
INSERT INTO action_comments VALUES (4, 5, 2, 'Tää on melko kärttyinen muutenkin!', '2012-11-22 12:03:26.42+02');


--
-- TOC entry 2040 (class 0 OID 66424)
-- Dependencies: 179
-- Data for Name: action_types; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO action_types VALUES (1, 'Korjauspyyntö');
INSERT INTO action_types VALUES (2, 'Hylkäys');
INSERT INTO action_types VALUES (3, 'Huomautus');
INSERT INTO action_types VALUES (4, 'Lisäaika');


--
-- TOC entry 2041 (class 0 OID 66432)
-- Dependencies: 181
-- Data for Name: actions; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO actions VALUES (1, 4, 4, 2, 3, 'Arviointi voisi olla tarkempi', '2012-11-22 12:03:26.42+02', NULL, NULL, NULL);
INSERT INTO actions VALUES (2, 4, 4, 3, 1, 'Epätarkka arvionti, parempi vaaditaan', '2012-11-22 12:03:26.42+02', '2013-02-10 00:00:00+02', NULL, NULL);
INSERT INTO actions VALUES (3, 4, 4, 3, 2, 'Ei tehnyt tehtävää huomautuksesta huolimatta', '2013-02-10 16:24:01+02', NULL, NULL, NULL);
INSERT INTO actions VALUES (4, 2, 2, 2, 4, 'Flunssassa, lisäaikaa annettu', '2012-11-22 12:03:26.42+02', '2013-01-14 23:00:00+02', NULL, NULL);
INSERT INTO actions VALUES (5, 3, 5, 2, 3, 'Rakentavampi arviointi olisi paikallaan', '2012-11-22 12:03:26.42+02', NULL, 3, '2012-12-02 19:57:52.78+02');


--
-- TOC entry 2044 (class 0 OID 66506)
-- Dependencies: 187
-- Data for Name: course_memberships; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO course_memberships VALUES (1, 2, 1, 2, '2012-12-02 19:57:52.78+02', '');
INSERT INTO course_memberships VALUES (2, 2, 2, NULL, NULL, '');
INSERT INTO course_memberships VALUES (3, 2, 3, NULL, NULL, '');
INSERT INTO course_memberships VALUES (4, 2, 4, NULL, NULL, '');
INSERT INTO course_memberships VALUES (5, 2, 5, NULL, NULL, 'Yksinhuoltaja sorsa');


--
-- TOC entry 2035 (class 0 OID 66358)
-- Dependencies: 169
-- Data for Name: courses; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO courses VALUES (1, 'T3 K2012', '2012-01-02 23:00:00+02', '2012-08-31 01:00:00+03');
INSERT INTO courses VALUES (2, 'T3 S2012', '2012-10-21 00:00:00+03', '2013-01-31 00:00:00+02');


--
-- TOC entry 2045 (class 0 OID 66532)
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
-- TOC entry 2039 (class 0 OID 66411)
-- Dependencies: 177
-- Data for Name: exercises; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO exercises VALUES (1, 1, 1, '2012-08-15 01:00:00+03');
INSERT INTO exercises VALUES (2, 2, 1, '2013-01-10 00:00:00+02');
INSERT INTO exercises VALUES (3, 2, 2, '2013-02-05 00:00:00+02');
INSERT INTO exercises VALUES (4, 2, 3, '2013-02-15 00:00:00+02');


--
-- TOC entry 2038 (class 0 OID 66393)
-- Dependencies: 175
-- Data for Name: groups; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO groups VALUES (1, 2, 2);
INSERT INTO groups VALUES (2, 2, 3);
INSERT INTO groups VALUES (3, 2, 4);


--
-- TOC entry 2043 (class 0 OID 66488)
-- Dependencies: 185
-- Data for Name: groups_students; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO groups_students VALUES (1, 1, 1);
INSERT INTO groups_students VALUES (2, 1, 2);
INSERT INTO groups_students VALUES (3, 2, 3);
INSERT INTO groups_students VALUES (4, 3, 4);
INSERT INTO groups_students VALUES (5, 2, 5);


--
-- TOC entry 2036 (class 0 OID 66366)
-- Dependencies: 171
-- Data for Name: students; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO students VALUES (1, 34243, 'Testaaja', 'Testi', 'testi.testaaja@uta.fi');
INSERT INTO students VALUES (2, 12345, 'Opiskelija', 'Ossi', 'ossi.opiskelija@uta.fi');
INSERT INTO students VALUES (3, 98765, 'Mänty', 'Jarmo', 'jarmo.manty@uta.fi');
INSERT INTO students VALUES (4, 43214, 'Luttinen', 'Usko', 'usko.luttinen@uta.fi');
INSERT INTO students VALUES (5, 26371, 'Sorsa', 'Pulla', 'pulla.sorsa@uta.fi');


--
-- TOC entry 2037 (class 0 OID 66379)
-- Dependencies: 173
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO users VALUES (1, '12345', 'Opettaja', 'Vastuu', 'vastuuope@uta.fi', 'ce192a5d433c20a11b143e63319f960f3a2361b8', true);
INSERT INTO users VALUES (2, '23456', 'Assistentti', 'Asseri', 'asseri.assistentti@uta.fi', 'ce192a5d433c20a11b143e63319f960f3a2361b8', false);
INSERT INTO users VALUES (3, '34567', 'Assistentti', 'Testi', 'testi.assistentti@uta.fi', 'ce192a5d433c20a11b143e63319f960f3a2361b8', false);
INSERT INTO users VALUES (4, '45678', 'Auttaja', 'Aapo', 'aapo.auttaja@uta.fi', 'ce192a5d433c20a11b143e63319f960f3a2361b8', false);


--
-- TOC entry 2011 (class 2606 OID 66475)
-- Name: action_comments_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY action_comments
    ADD CONSTRAINT action_comments_pkey PRIMARY KEY (id);


--
-- TOC entry 2007 (class 2606 OID 66429)
-- Name: action_types_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY action_types
    ADD CONSTRAINT action_types_pkey PRIMARY KEY (id);


--
-- TOC entry 2009 (class 2606 OID 66438)
-- Name: actions_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_pkey PRIMARY KEY (id);


--
-- TOC entry 2015 (class 2606 OID 66514)
-- Name: course_memberships_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY course_memberships
    ADD CONSTRAINT course_memberships_pkey PRIMARY KEY (id);


--
-- TOC entry 1993 (class 2606 OID 66363)
-- Name: courses_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY courses
    ADD CONSTRAINT courses_pkey PRIMARY KEY (id);


--
-- TOC entry 2017 (class 2606 OID 66537)
-- Name: courses_users_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY courses_users
    ADD CONSTRAINT courses_users_pkey PRIMARY KEY (id);


--
-- TOC entry 2005 (class 2606 OID 66416)
-- Name: exercises_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY exercises
    ADD CONSTRAINT exercises_pkey PRIMARY KEY (id);


--
-- TOC entry 2003 (class 2606 OID 66398)
-- Name: groups_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_pkey PRIMARY KEY (id);


--
-- TOC entry 2013 (class 2606 OID 66493)
-- Name: groups_students_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY groups_students
    ADD CONSTRAINT groups_students_pkey PRIMARY KEY (id);


--
-- TOC entry 1995 (class 2606 OID 66374)
-- Name: students_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY students
    ADD CONSTRAINT students_pkey PRIMARY KEY (id);


--
-- TOC entry 1997 (class 2606 OID 66376)
-- Name: students_student_number_key; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY students
    ADD CONSTRAINT students_student_number_key UNIQUE (student_number);


--
-- TOC entry 1999 (class 2606 OID 66390)
-- Name: users_basic_user_account_key; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_basic_user_account_key UNIQUE (basic_user_account);


--
-- TOC entry 2001 (class 2606 OID 66388)
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: -; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 2026 (class 2606 OID 66476)
-- Name: action_comments_action_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY action_comments
    ADD CONSTRAINT action_comments_action_id_fkey FOREIGN KEY (action_id) REFERENCES actions(id);


--
-- TOC entry 2027 (class 2606 OID 66481)
-- Name: action_comments_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY action_comments
    ADD CONSTRAINT action_comments_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 2024 (class 2606 OID 66454)
-- Name: actions_action_type_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_action_type_id_fkey FOREIGN KEY (action_type_id) REFERENCES action_types(id);


--
-- TOC entry 2023 (class 2606 OID 66449)
-- Name: actions_exercise_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_exercise_id_fkey FOREIGN KEY (exercise_id) REFERENCES exercises(id);


--
-- TOC entry 2025 (class 2606 OID 66459)
-- Name: actions_handled_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_handled_id_fkey FOREIGN KEY (handled_id) REFERENCES users(id);


--
-- TOC entry 2022 (class 2606 OID 66444)
-- Name: actions_student_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_student_id_fkey FOREIGN KEY (student_id) REFERENCES students(id);


--
-- TOC entry 2021 (class 2606 OID 66439)
-- Name: actions_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 2030 (class 2606 OID 66515)
-- Name: course_memberships_course_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY course_memberships
    ADD CONSTRAINT course_memberships_course_id_fkey FOREIGN KEY (course_id) REFERENCES courses(id);


--
-- TOC entry 2032 (class 2606 OID 66525)
-- Name: course_memberships_quit_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY course_memberships
    ADD CONSTRAINT course_memberships_quit_id_fkey FOREIGN KEY (quit_id) REFERENCES users(id);


--
-- TOC entry 2031 (class 2606 OID 66520)
-- Name: course_memberships_student_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY course_memberships
    ADD CONSTRAINT course_memberships_student_id_fkey FOREIGN KEY (student_id) REFERENCES students(id);


--
-- TOC entry 2033 (class 2606 OID 66538)
-- Name: courses_users_course_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY courses_users
    ADD CONSTRAINT courses_users_course_id_fkey FOREIGN KEY (course_id) REFERENCES courses(id);


--
-- TOC entry 2034 (class 2606 OID 66543)
-- Name: courses_users_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY courses_users
    ADD CONSTRAINT courses_users_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 2020 (class 2606 OID 66417)
-- Name: exercises_course_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY exercises
    ADD CONSTRAINT exercises_course_id_fkey FOREIGN KEY (course_id) REFERENCES courses(id);


--
-- TOC entry 2018 (class 2606 OID 66399)
-- Name: groups_course_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_course_id_fkey FOREIGN KEY (course_id) REFERENCES courses(id);


--
-- TOC entry 2028 (class 2606 OID 66494)
-- Name: groups_students_group_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups_students
    ADD CONSTRAINT groups_students_group_id_fkey FOREIGN KEY (group_id) REFERENCES groups(id);


--
-- TOC entry 2029 (class 2606 OID 66499)
-- Name: groups_students_student_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups_students
    ADD CONSTRAINT groups_students_student_id_fkey FOREIGN KEY (student_id) REFERENCES students(id);


--
-- TOC entry 2019 (class 2606 OID 66404)
-- Name: groups_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 2052 (class 0 OID 0)
-- Dependencies: 6
-- Name: public; Type: ACL; Schema: -; Owner: -
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2012-12-02 19:59:31

--
-- PostgreSQL database dump complete
--

