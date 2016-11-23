# Bucket concept

This concept has been created to test the concept of compartimentalizing sitemap entries into buckets.

A bucket is:
* a container that has a fixed amount of slots
* has slots that can only ever hold one entry

This means that there only has to be one 'active' bucket which does not have all places filled in which new entries will be appended to until all the slots have been filled.
On that case a new bucket will be initialzed with the new entry added to it.

## Conclusion

It is not benificial to implement this in the project.

The result of this proof of concept is that buckets introduce additional complexity that is not needed for pure functionality.
It also forces the use of additional meta-data to be saved "somewhere" to keep track of the latest bucket.
