DECLARE @New_Data VARCHAR(MAX)
DECLARE @ChangeLog VARCHAR(MAX)

----- PARAMETERS -----
SET @New_Data = :New_Data
SET @ChangeLog = :ChangeLog

----- BEGIN MAIN -----
SET NOCOUNT ON
BEGIN TRANSACTION
		insert into [VCI-Intranet].[dbo].[BI360_Change_History] 
					([New_Data],[ChangeLog],[Old_Version_G1],[Old_Data_G1],[Old_Id_G1],[Old_Version_G2],[Old_Data_G2],[Old_Id_G2]) 
					values(@New_Data,@ChangeLog,(select Version from [OSRR4-G1].[dbo].Models),(select Data from [OSRR4-G1].[dbo].Models),(select Id from [OSRR4-G1].[dbo].Models),(select Version from [OSRR4-G2].[dbo].Models),(select Data from [OSRR4-G2].[dbo].Models),(select Id from [OSRR4-G2].[dbo].Models));
		--insert into [VCI-Intranet].[dbo].[BI360_Change_History] 
		--			([New_Data],[ChangeLog],[Old_Version_G1],[Old_Data_G1],[Old_Id_G1],[Old_Version_G2],[Old_Data_G2],[Old_Id_G2]) 
		--			values(@New_Data,@ChangeLog,(select Version from [OSRR4-G1].[dbo].Models),(select Data from [OSRR4-G1].[dbo].Models),(select Id from [OSRR4-G1].[dbo].Models),(select Version from [OSRR4-G2].[dbo].Models),(select Data from [OSRR4-G2].[dbo].Models),(select Id from [OSRR4-G2].[dbo].Models));
		update [OSRR4-G1].[dbo].Models set Data=@New_Data where Version=(select Version from [OSRR4-G1].[dbo].Models) and Id=(select Id from [OSRR4-G1].[dbo].Models);
		update [OSRR4-G2].[dbo].Models set Data=@New_Data where Version=(select Version from [OSRR4-G2].[dbo].Models) and Id=(select Id from [OSRR4-G2].[dbo].Models);	
		--update [VCI-Intranet].[dbo].Models_copy set Data=@New_Data where Version=(select Version from [OSRR4-G1].[dbo].Models) and Id=(select Id from [OSRR4-G1].[dbo].Models);		
IF @@ERROR = 0 COMMIT TRANSACTION ELSE ROLLBACK TRANSACTION
SET NOCOUNT OFF
----- END MAIN -----