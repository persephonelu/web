namespace php word_process
struct WordResult {
  1: i32 status,
  2: list<string> wordsegs,
  3: optional list<string> postags,
  4: optional list<double> weights
}

struct RequestBrief {
  1: string category,
  2: string brief,
  3: optional string appname
}

struct TagPair {
  1: string tag,
  2: double weight
}

struct ResponseBrief {
  1: i32 status,
  2: list<TagPair> brieftags
}

service WordProcess {
  WordResult segRoutine(1: string query),
  WordResult tagRoutine(1: string query),
  WordResult rankRoutine(1: string query),
  ResponseBrief getBriefTags(1: RequestBrief req)
}
