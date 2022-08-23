const protocol = "http";
const baseUrl = "127.0.0.1";
const port = "8000";

export const serverApiUrl = protocol + "://" + baseUrl + ":" + port + "/api/";
export const serverPersonUrl = serverApiUrl + "person/"; // GET shows, POST sets, PUT modify, DEL deletes
export const serverSearchPersonByEMailUrl = serverPersonUrl + "search/email/";
export const serverSearchPersonByPhoneUrl = serverPersonUrl + "search/phone/";