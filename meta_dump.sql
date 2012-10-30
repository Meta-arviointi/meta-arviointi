--
-- PostgreSQL database dump
--

-- Dumped from database version 9.2.0
-- Dumped by pg_dump version 9.2.0
-- Started on 2012-10-29 22:18:44

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 184 (class 3079 OID 11727)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2008 (class 0 OID 0)
-- Dependencies: 184
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 181 (class 1259 OID 24711)
-- Name: actions; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE actions (
    id integer NOT NULL,
    user_id integer NOT NULL,
    student_id integer NOT NULL,
    exercise_id integer NOT NULL,
    type character(20),
    comment character varying(255),
    created timestamp with time zone
);

ALTER TABLE public.actions OWNER TO postgres;

--
-- TOC entry 180 (class 1259 OID 24709)
-- Name: actions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE actions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.actions_id_seq OWNER TO postgres;

--
-- TOC entry 2009 (class 0 OID 0)
-- Dependencies: 180
-- Name: actions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE actions_id_seq OWNED BY actions.id;


--
-- TOC entry 2010 (class 0 OID 0)
-- Dependencies: 180
-- Name: actions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('actions_id_seq', 1, false);


--
-- TOC entry 175 (class 1259 OID 24648)
-- Name: courses; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE courses (
    id integer NOT NULL,
    name character varying(255) NOT NULL, 
    startdate timestamp with time zone NOT NULL,
    enddate timestamp with time zone NOT NULL
);


ALTER TABLE public.courses OWNER TO postgres;

--
-- TOC entry 174 (class 1259 OID 24646)
-- Name: courses_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE courses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.courses_id_seq OWNER TO postgres;

--
-- TOC entry 2011 (class 0 OID 0)
-- Dependencies: 174
-- Name: courses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE courses_id_seq OWNED BY courses.id;


--
-- TOC entry 2012 (class 0 OID 0)
-- Dependencies: 174
-- Name: courses_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('courses_id_seq', 1, true);


--
-- TOC entry 179 (class 1259 OID 24674)
-- Name: exercises; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE exercises (
    id integer NOT NULL,
    course_id integer NOT NULL,
    exercise_number integer NOT NULL,
    deadline timestamp with time zone NOT NULL
);


ALTER TABLE public.exercises OWNER TO postgres;

--
-- TOC entry 178 (class 1259 OID 24672)
-- Name: exercises_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE exercises_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.exercises_id_seq OWNER TO postgres;

--
-- TOC entry 2013 (class 0 OID 0)
-- Dependencies: 178
-- Name: exercises_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE exercises_id_seq OWNED BY exercises.id;


--
-- TOC entry 2014 (class 0 OID 0)
-- Dependencies: 178
-- Name: exercises_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('exercises_id_seq', 1, false);


--
-- TOC entry 177 (class 1259 OID 24656)
-- Name: groups; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE groups (
    id integer NOT NULL,
    course_id integer NOT NULL,
    user_id integer NOT NULL
);


ALTER TABLE public.groups OWNER TO postgres;

--
-- TOC entry 176 (class 1259 OID 24654)
-- Name: groups_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.groups_id_seq OWNER TO postgres;

--
-- TOC entry 2015 (class 0 OID 0)
-- Dependencies: 176
-- Name: groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE groups_id_seq OWNED BY groups.id;


--
-- TOC entry 2016 (class 0 OID 0)
-- Dependencies: 176
-- Name: groups_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('groups_id_seq', 3, true);


--
-- TOC entry 183 (class 1259 OID 32808)
-- Name: notes; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE notes (
    id integer NOT NULL,
    student_id integer NOT NULL,
    user_id integer NOT NULL,
    note character varying(255) NOT NULL,
    created timestamp with time zone
);


ALTER TABLE public.notes OWNER TO postgres;

--
-- TOC entry 182 (class 1259 OID 32806)
-- Name: notes_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE notes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.notes_id_seq OWNER TO postgres;

--
-- TOC entry 2017 (class 0 OID 0)
-- Dependencies: 182
-- Name: notes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE notes_id_seq OWNED BY notes.id;


--
-- TOC entry 2018 (class 0 OID 0)
-- Dependencies: 182
-- Name: notes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('notes_id_seq', 1, true);



--
-- TOC entry 170 (class 1259 OID 16403)
-- Name: students; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE students (
    id integer NOT NULL,
    student_number integer UNIQUE NOT NULL,
    last_name character varying(255) NOT NULL,
    first_name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    group_id integer
);

ALTER TABLE public.students OWNER TO postgres;

--
-- TOC entry 171 (class 1259 OID 16409)
-- Name: students_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE students_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.students_id_seq OWNER TO postgres;

--
-- TOC entry 2021 (class 0 OID 0)
-- Dependencies: 171
-- Name: students_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE students_id_seq OWNED BY students.id;


--
-- TOC entry 2022 (class 0 OID 0)
-- Dependencies: 171
-- Name: students_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('students_id_seq', 6, true);


--
-- TOC entry 173 (class 1259 OID 24637)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE users (
    id integer NOT NULL,
    first_name character varying(255) NOT NULL,
    last_name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    is_admin boolean NOT NULL ,
    password character varying(50),
    basic_user_account character varying(30)
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 172 (class 1259 OID 24635)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO postgres;

--
-- TOC entry 2023 (class 0 OID 0)
-- Dependencies: 172
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- TOC entry 2024 (class 0 OID 0)
-- Dependencies: 172
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('users_id_seq', 4, true);


--
-- TOC entry 1967 (class 2604 OID 24714)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY actions ALTER COLUMN id SET DEFAULT nextval('actions_id_seq'::regclass);


--
-- TOC entry 1964 (class 2604 OID 24651)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY courses ALTER COLUMN id SET DEFAULT nextval('courses_id_seq'::regclass);


--
-- TOC entry 1966 (class 2604 OID 24677)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY exercises ALTER COLUMN id SET DEFAULT nextval('exercises_id_seq'::regclass);


--
-- TOC entry 1965 (class 2604 OID 24659)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY groups ALTER COLUMN id SET DEFAULT nextval('groups_id_seq'::regclass);


--
-- TOC entry 1968 (class 2604 OID 32811)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY notes ALTER COLUMN id SET DEFAULT nextval('notes_id_seq'::regclass);




--
-- TOC entry 1962 (class 2604 OID 16412)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY students ALTER COLUMN id SET DEFAULT nextval('students_id_seq'::regclass);


--
-- TOC entry 1963 (class 2604 OID 24640)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- TOC entry 1999 (class 0 OID 24711)
-- Dependencies: 181
-- Data for Name: actions; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 1996 (class 0 OID 24648)
-- Dependencies: 175
-- Data for Name: courses; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO courses VALUES (1, 'T3 S2012', '2012-10-21', '2013-01-31');


--
-- TOC entry 1998 (class 0 OID 24674)
-- Dependencies: 179
-- Data for Name: exercises; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 1997 (class 0 OID 24656)
-- Dependencies: 177
-- Data for Name: groups; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO groups VALUES (1, 1, 2);
INSERT INTO groups VALUES (2, 1, 3);
INSERT INTO groups VALUES (3, 1, 4);


--
-- TOC entry 2000 (class 0 OID 32808)
-- Dependencies: 183
-- Data for Name: notes; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO notes VALUES (1, 2, 3, 'tämä on nootti', '2012-10-29 22:17:15.91+02');
INSERT INTO notes VALUES (2, 4, 3, 'Reipas kaveri', '2012-10-30 15:17:50.91+02');
INSERT INTO notes VALUES (3, 4, 2, 'Totta!', '2012-10-30 15:19:50.90+02');


--
-- TOC entry 1994 (class 0 OID 16403)
-- Dependencies: 170
-- Data for Name: students; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO students VALUES (2, 3423, 'Testaaja                                                                                                                                                                                                                                                       ', 'Testi                                                                                                                                                                                                                                                          ', 
'testi.testaaja@uta.fi', NULL);
INSERT INTO students VALUES (3,12345, 'Opiskelija                                                                                                                                                                                                                                                     ', 'Ossi                                                                                                                                                                                                                                                           ',  'ossi.opiskelija@uta.fi                                                                                                                                                                                                                                         ', 1);
INSERT INTO students VALUES (4,98765, 'Mänty                                                                                                                                                                                                                                                          ', 'Jarmo                                                                                                                                                                                                                                                          ',  'jarmo.manty@uta.fi                                                                                                                                                                                                                                             ', 3);
INSERT INTO students VALUES (5,43214, 'Luttinen                                                                                                                                                                                                                                                       ', 'Usko                                                                                                                                                                                                                                                           ',  'usko.luttinen@uta.fi                                                                                                                                                                                                                                           ', 3);
INSERT INTO students VALUES (6, 26371, 'Sorsa                                                                                                                                                                                                                                                          ', 'Pulla                                                                                                                                                                                                                                                          ', 'pulla.sorsa@uta.fi                                                                                                                                                                                                                                             ', 2);


--
-- TOC entry 1995 (class 0 OID 24637)
-- Dependencies: 173
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO users VALUES (1, 'Opettaja', 'Vastuu', 'vastuuope@uta.fi', true);
INSERT INTO users VALUES (2, 'Assistentti', 'Asseri','asseri.assistentti@uta.fi', false);
INSERT INTO users VALUES (3, 'Assistentti', 'Testi','testi.assistentti@uta.fi', false);
INSERT INTO users VALUES (4, 'Auttaja', 'Aapo', 'aapo.auttaja@uta.fi', false);


--
-- TOC entry 1982 (class 2606 OID 24716)
-- Name: actions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_pkey PRIMARY KEY (id);


--
-- TOC entry 1976 (class 2606 OID 24653)
-- Name: courses_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY courses
    ADD CONSTRAINT courses_pkey PRIMARY KEY (id);


--
-- TOC entry 1980 (class 2606 OID 24679)
-- Name: exercises_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY exercises
    ADD CONSTRAINT exercises_pkey PRIMARY KEY (id);


--
-- TOC entry 1978 (class 2606 OID 24661)
-- Name: groups_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_pkey PRIMARY KEY (id);


--
-- TOC entry 1972 (class 2606 OID 16416)
-- Name: students_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY students
    ADD CONSTRAINT students_pkey PRIMARY KEY (id);


--
-- TOC entry 1974 (class 2606 OID 24645)
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 1990 (class 2606 OID 24727)
-- Name: actions_exercise_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_exercise_id_fkey FOREIGN KEY (exercise_id) REFERENCES exercises(id);


--
-- TOC entry 1989 (class 2606 OID 24722)
-- Name: actions_student_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_student_id_fkey FOREIGN KEY (student_id) REFERENCES students(id);


--
-- TOC entry 1988 (class 2606 OID 24717)
-- Name: actions_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY actions
    ADD CONSTRAINT actions_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 1987 (class 2606 OID 24680)
-- Name: exercises_course_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY exercises
    ADD CONSTRAINT exercises_course_id_fkey FOREIGN KEY (course_id) REFERENCES courses(id);


--
-- TOC entry 1985 (class 2606 OID 24662)
-- Name: groups_course_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_course_id_fkey FOREIGN KEY (course_id) REFERENCES courses(id);


--
-- TOC entry 1986 (class 2606 OID 24667)
-- Name: groups_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);


--
-- TOC entry 1991 (class 2606 OID 32812)
-- Name: notes_student_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY notes
    ADD CONSTRAINT notes_student_id_fkey FOREIGN KEY (student_id) REFERENCES students(id);


--
-- TOC entry 1992 (class 2606 OID 32817)
-- Name: notes_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY notes
    ADD CONSTRAINT notes_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id);



--
-- TOC entry 1984 (class 2606 OID 24732)
-- Name: students_group_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY students
    ADD CONSTRAINT students_group_id_fkey FOREIGN KEY (group_id) REFERENCES groups(id);


--
-- TOC entry 2007 (class 0 OID 0)
-- Dependencies: 6
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2012-10-29 22:18:44

--
-- PostgreSQL database dump complete
--

