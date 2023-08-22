create table `tasks` (
  taskId : serial,
  creatorId : bigint,
  
);


create table `tokens` (
  token : varchar(64),
  userId : bigint,
  expires : 


create table `users` (
  userId : serial,
  username : varchar(200),
  passwordHash : varchar(128),
  salt : varchar(128),
  primary key (userId),
  key(username)
);