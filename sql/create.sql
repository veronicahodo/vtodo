create table `tasks` (
  taskId : serial,
  creatorId : bigint,
  
);


create table `tokens` (
  token : varchar(64),
  userId : bigint,
  expires : 
